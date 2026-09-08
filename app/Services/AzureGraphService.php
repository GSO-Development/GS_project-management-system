<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AzureGraphService
{
    /**
     * Obtain a client-credentials access token from Azure AD.
     */
    public static function getAccessToken(): ?string
    {
        $tenantId     = env('AZURE_TENANT_ID');
        $clientId     = env('AZURE_CLIENT_ID');
        $clientSecret = env('AZURE_CLIENT_SECRET');

        try {
            $response = Http::asForm()->post(
                "https://login.microsoftonline.com/{$tenantId}/oauth2/v2.0/token",
                [
                    'grant_type'    => 'client_credentials',
                    'client_id'     => $clientId,
                    'client_secret' => $clientSecret,
                    'scope'         => 'https://graph.microsoft.com/.default',
                ]
            );

            if ($response->successful()) {
                return $response->json('access_token');
            }

            Log::error('AzureGraphService: Token request failed — ' . $response->body());
            return null;

        } catch (\Exception $e) {
            Log::error('AzureGraphService: Token exception — ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Search Azure AD users by display name or email.
     * Uses OData $filter with startswith — supports partial name AND full/partial email.
     */
    public static function searchUsers(string $query, int $limit = 10): array
    {
        $query = trim($query);

        if (strlen($query) < 2) {
            return [];
        }

        $token = self::getAccessToken();
        if (!$token) {
            return [];
        }

        // Escape single quotes for OData (OData requires '' to escape ')
        $safeQuery = str_replace("'", "''", $query);

        try {
            // Strategy A: $filter with startswith on displayName + mail + userPrincipalName
            // This correctly handles partial name and full/partial email searches
            $filter = "startswith(displayName,'{$safeQuery}')"
                    . " or startswith(mail,'{$safeQuery}')"
                    . " or startswith(userPrincipalName,'{$safeQuery}')";

            $response = Http::withToken($token)
                ->get('https://graph.microsoft.com/v1.0/users', [
                    '$filter' => $filter,
                    '$select' => 'id,displayName,mail,userPrincipalName,mobilePhone,department,companyName',
                    '$top'    => $limit,
                ]);

            if ($response->successful()) {
                $users = $response->json('value') ?? [];

                // If filter found results, return them
                if (!empty($users)) {
                    return self::normalizeUsers($users);
                }
            } else {
                Log::warning('AzureGraphService: $filter query failed (' . $response->status() . '): ' . $response->body());
            }

            // Strategy B: $search with ConsistencyLevel (broader — works on indexed attrs)
            $searchResponse = Http::withToken($token)
                ->withHeaders(['ConsistencyLevel' => 'eventual'])
                ->get('https://graph.microsoft.com/v1.0/users', [
                    '$search' => '"displayName:' . $safeQuery . '" OR "mail:' . $safeQuery . '"',
                    '$select' => 'id,displayName,mail,userPrincipalName,mobilePhone,department,companyName',
                    '$count'  => 'true',
                    '$top'    => $limit,
                ]);

            if ($searchResponse->successful()) {
                $users = $searchResponse->json('value') ?? [];
                return self::normalizeUsers($users);
            }

            Log::error('AzureGraphService: Both search strategies failed. Last error: ' . $searchResponse->body());
            return [];

        } catch (\Exception $e) {
            Log::error('AzureGraphService: Search exception — ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Search Azure AD corporate entities (user departments and verified organization domains).
     * Returns normalized subsidiary suggestions from Microsoft Graph API.
     */
    public static function searchSubsidiaries(string $query, int $limit = 8): array
    {
        $query = trim($query);
        if (strlen($query) < 2) {
            return [];
        }

        $token = self::getAccessToken();
        if (!$token) {
            return [];
        }

        $results = [];
        $lowerQuery = strtolower($query);

        try {
            // 1. Fetch Users Departments from Graph API
            $userResp = Http::withToken($token)
                ->get('https://graph.microsoft.com/v1.0/users', [
                    '$top'    => 100,
                    '$select' => 'department,companyName',
                ]);

            if ($userResp->successful()) {
                $rawUsers = $userResp->json('value') ?? [];
                $departments = array_unique(array_filter(array_column($rawUsers, 'department')));

                foreach ($departments as $dept) {
                    if (str_contains(strtolower($dept), $lowerQuery)) {
                        $cleanName = self::formatSubsidiaryName($dept);
                        $code      = self::generateCodeFromName($cleanName);
                        $slug      = str()->slug($cleanName);

                        $results[$slug] = [
                            'id'            => 'az-dept-' . $slug,
                            'name'          => $cleanName,
                            'code'          => $code,
                            'description'   => 'Azure AD Corporate Department (' . $dept . ')',
                            'contact_email' => strtolower($code) . '@georgesteuart.lk',
                            'source'        => 'Azure AD Department',
                        ];
                    }
                }
            }

            // 2. Fetch Organization Verified Domains from Graph API
            $orgResp = Http::withToken($token)->get('https://graph.microsoft.com/v1.0/organization');

            if ($orgResp->successful()) {
                $orgData = $orgResp->json('value.0') ?? [];
                $domains = $orgData['verifiedDomains'] ?? [];

                foreach ($domains as $d) {
                    $domainName = strtolower($d['name'] ?? '');
                    // Skip onmicrosoft.com default domains
                    if (str_contains($domainName, 'onmicrosoft.com') || str_contains($domainName, 'codetwo')) {
                        continue;
                    }

                    if (str_contains($domainName, $lowerQuery)) {
                        $prefix = explode('.', $domainName)[0]; // e.g. "gshealth"
                        $name   = self::formatSubsidiaryNameFromDomain($prefix);
                        $code   = self::generateCodeFromName($name);
                        $slug   = 'az-dom-' . str()->slug($domainName);

                        if (!isset($results[$slug])) {
                            $results[$slug] = [
                                'id'            => $slug,
                                'name'          => $name,
                                'code'          => $code,
                                'description'   => 'Azure AD Verified Domain (' . $domainName . ')',
                                'contact_email' => 'info@' . $domainName,
                                'source'        => 'Azure AD Verified Domain',
                            ];
                        }
                    }
                }
            }

            return array_slice(array_values($results), 0, $limit);

        } catch (\Exception $e) {
            Log::error('AzureGraphService: searchSubsidiaries exception — ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Format raw Azure department text into clean title-cased corporate name.
     */
    private static function formatSubsidiaryName(string $raw): string
    {
        $clean = str_ireplace(['(PVT) LTD', 'LIMITED', 'PLC', 'LTD'], '', $raw);
        $clean = trim(preg_replace('/\s+/', ' ', $clean));
        return ucwords(strtolower($clean));
    }

    /**
     * Format domain prefix (e.g. gshealth -> George Steuart Health).
     */
    private static function formatSubsidiaryNameFromDomain(string $prefix): string
    {
        if (str_starts_with($prefix, 'gs')) {
            $rest = substr($prefix, 2);
            return 'George Steuart ' . ucfirst($rest);
        }
        return ucwords($prefix);
    }

    /**
     * Generate 3-6 letter uppercase code from name.
     */
    private static function generateCodeFromName(string $name): string
    {
        $ignoreWords = ['pvt', 'ltd', 'limited', 'private', 'inc', 'corp', 'co', 'company', '&', 'and'];
        $words = preg_split('/\s+/', $name);
        $letters = '';

        foreach ($words as $w) {
            $cleanWord = preg_replace('/[^a-zA-Z0-9]/', '', $w);
            if (!empty($cleanWord) && !in_array(strtolower($cleanWord), $ignoreWords)) {
                $letters .= strtoupper(substr($cleanWord, 0, 1));
            }
        }

        if (!str_starts_with($letters, 'GS')) {
            $code = 'GS' . $letters;
        } else {
            $code = $letters;
        }

        return strtoupper(substr($code, 0, 8));
    }

    /**
     * Normalize raw Microsoft Graph API user array to structured schema.
     */
    private static function normalizeUsers(array $rawUsers): array
    {
        $normalized = [];

        foreach ($rawUsers as $user) {
            $email = $user['mail'] ?? $user['userPrincipalName'] ?? null;
            if (!$email) {
                continue;
            }

            $displayName = $user['displayName'] ?? 'Azure User';

            $normalized[] = [
                'id'                => $user['id'] ?? null,
                'azure_id'          => $user['id'] ?? null,
                'displayName'       => $displayName,
                'name'              => $displayName,
                'mail'              => strtolower($email),
                'email'             => strtolower($email),
                'userPrincipalName' => $user['userPrincipalName'] ?? null,
                'phone'             => $user['mobilePhone'] ?? null,
                'mobilePhone'       => $user['mobilePhone'] ?? null,
                'department'        => $user['department'] ?? null,
                'company'           => $user['companyName'] ?? null,
                'companyName'       => $user['companyName'] ?? null,
            ];
        }

        return $normalized;
    }

    /**
     * Create an event in a user's Microsoft 365 / Outlook calendar via Microsoft Graph API.
     */
    public static function createCalendarEvent(string $userPrincipalName, array $eventData): array
    {
        $token = self::getAccessToken();
        if (!$token) {
            return ['success' => false, 'message' => 'Microsoft Azure AD is not connected or token expired.'];
        }

        try {
            $startDate = $eventData['start_date'];
            $endDate = $eventData['end_date'] ?? $startDate;
            $startTime = !empty($eventData['start_time']) ? $eventData['start_time'] : '09:00:00';
            $endTime = !empty($eventData['end_time']) ? $eventData['end_time'] : '10:00:00';

            $startDateTime = \Carbon\Carbon::parse("{$startDate} {$startTime}")->format('Y-m-d\TH:i:s');
            $endDateTime = \Carbon\Carbon::parse("{$endDate} {$endTime}")->format('Y-m-d\TH:i:s');
            $timezone = config('app.timezone', 'Asia/Colombo');

            $attendees = [];
            if (!empty($eventData['attendee_emails'])) {
                foreach ($eventData['attendee_emails'] as $attEmail) {
                    $attendees[] = [
                        'emailAddress' => [
                            'address' => $attEmail,
                            'name'    => $attEmail,
                        ],
                        'type' => 'required',
                    ];
                }
            }

            $payload = [
                'subject' => $eventData['title'],
                'body' => [
                    'contentType' => 'HTML',
                    'content'     => nl2br(e($eventData['description'] ?? '')),
                ],
                'start' => [
                    'dateTime' => $startDateTime,
                    'timeZone' => $timezone,
                ],
                'end' => [
                    'dateTime' => $endDateTime,
                    'timeZone' => $timezone,
                ],
                'location' => [
                    'displayName' => $eventData['location'] ?? ($eventData['meeting_link'] ?? 'GS NexusPM'),
                ],
                'isAllDay' => !empty($eventData['is_all_day']),
            ];

            if (!empty($attendees)) {
                $payload['attendees'] = $attendees;
            }

            $url = "https://graph.microsoft.com/v1.0/users/" . urlencode($userPrincipalName) . "/events";

            $response = \Illuminate\Support\Facades\Http::withToken($token)
                ->withHeaders(['Prefer' => 'outlook.timezone="' . $timezone . '"'])
                ->post($url, $payload);

            if ($response->successful()) {
                $data = $response->json();
                return [
                    'success' => true,
                    'microsoft_event_id' => $data['id'] ?? null,
                    'web_link' => $data['webLink'] ?? null,
                    'message' => 'Successfully synchronized to Microsoft Outlook Calendar.',
                ];
            }

            \Illuminate\Support\Facades\Log::warning('AzureGraphService: Calendar event creation returned error — ' . $response->body());
            return [
                'success' => false,
                'message' => 'Microsoft Graph API: ' . ($response->json('error.message') ?? 'Permissions restricted or user calendar not accessible.'),
            ];
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('AzureGraphService: Calendar event exception — ' . $e->getMessage());
            return [
                'success' => false,
                'message' => $e->getMessage(),
            ];
        }
    }
}

