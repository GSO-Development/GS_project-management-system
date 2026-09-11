<?php

namespace App\Services;

use App\Models\Subsidiary;
use App\Models\User;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Spatie\Permission\Models\Role;

class AzureUserService
{
    /**
     * Fetch all users from Microsoft Azure AD (Entra ID) via Microsoft Graph API
     * and synchronize them into the local database under their respective subsidiaries.
     */
    public static function syncAzureUsers(): array
    {
        $clientId     = config('services.azure.client_id') ?: env('AZURE_CLIENT_ID');
        $tenantId     = config('services.azure.tenant') ?: env('AZURE_TENANT_ID');
        $clientSecret = config('services.azure.client_secret') ?: env('AZURE_CLIENT_SECRET');

        if (!$clientId || !$tenantId || !$clientSecret) {
            Log::warning('Azure credentials missing in .env. Skipping synchronization.');
            return [
                'success' => false,
                'message' => 'Azure AD credentials are not fully configured in your .env file.',
                'count'   => 0
            ];
        }

        try {
            // 1. Get Access Token from Microsoft identity platform
            $tokenResponse = Http::asForm()->post("https://login.microsoftonline.com/{$tenantId}/oauth2/v2.0/token", [
                'grant_type'    => 'client_credentials',
                'client_id'     => $clientId,
                'client_secret' => $clientSecret,
                'scope'         => 'https://graph.microsoft.com/.default',
            ]);

            if ($tokenResponse->failed()) {
                Log::error('Azure AD Token Request Failed: ' . $tokenResponse->body());
                return [
                    'success' => false,
                    'message' => 'Failed to retrieve access token from Azure AD: ' . ($tokenResponse->json('error_description') ?? $tokenResponse->body()),
                    'count'   => 0
                ];
            }

            $accessToken = $tokenResponse->json('access_token');

            // 2. Fetch users from Microsoft Graph API
            // Select id, displayName, mail, userPrincipalName, department, companyName, mobilePhone
            $graphUrl = "https://graph.microsoft.com/v1.0/users?\$select=id,displayName,mail,userPrincipalName,department,companyName,mobilePhone&\$top=999";
            
            $usersResponse = Http::withToken($accessToken)->get($graphUrl);

            if ($usersResponse->failed()) {
                Log::error('Microsoft Graph Users Request Failed: ' . $usersResponse->body());
                return [
                    'success' => false,
                    'message' => 'Failed to fetch users from Microsoft Graph API: ' . ($usersResponse->json('error.message') ?? $usersResponse->body()),
                    'count'   => 0
                ];
            }

            $azureUsers = $usersResponse->json('value') ?? [];
            $syncCount = 0;

            $subsidiaries = Subsidiary::all();
            $collaboratorRole = Role::findOrCreate('collaborator', 'web');

            foreach ($azureUsers as $azureUser) {
                $email = $azureUser['mail'] ?? $azureUser['userPrincipalName'] ?? null;
                if (!$email) {
                    continue;
                }

                $azureId = $azureUser['id'];
                $name = $azureUser['displayName'] ?? $email;
                $phone = $azureUser['mobilePhone'] ?? null;
                $department = $azureUser['department'] ?? null;
                $companyName = $azureUser['companyName'] ?? null;

                // Resolve subsidiary
                $subsidiaryId = self::resolveSubsidiaryId($subsidiaries, $department, $companyName, $email);

                // Find or create local user
                $user = User::where('azure_id', $azureId)->first()
                    ?? User::where('email', $email)->first();

                if ($user) {
                    $user->update([
                        'azure_id'     => $azureId,
                        'name'         => $name,
                        'phone_number' => $phone ?? $user->phone_number,
                        'subsidiary_id' => $subsidiaryId ?? $user->subsidiary_id,
                    ]);
                } else {
                    $user = User::create([
                        'name'                 => $name,
                        'email'                => $email,
                        'azure_id'             => $azureId,
                        'phone_number'         => $phone,
                        'password'             => bcrypt(str()->random(32)),
                        'subsidiary_id'        => $subsidiaryId,
                        'is_active'            => true,
                        'must_change_password' => false,
                        'email_verified_at'    => now(),
                    ]);

                    // Assign collaborator role by default
                    if (!$user->hasAnyRole(['super_admin', 'project_manager', 'collaborator'])) {
                        $user->assignRole($collaboratorRole);
                    }
                }

                $syncCount++;
            }

            return [
                'success' => true,
                'message' => "Successfully synchronized {$syncCount} users from Microsoft Azure AD.",
                'count'   => $syncCount
            ];

        } catch (\Exception $e) {
            Log::error('Azure AD Synchronization Exception: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => 'An error occurred during Azure synchronization: ' . $e->getMessage(),
                'count'   => 0
            ];
        }
    }

    /**
     * Resolve the local subsidiary ID from Azure AD user properties.
     */
    private static function resolveSubsidiaryId($subsidiaries, ?string $department, ?string $companyName, string $email): ?int
    {
        // 1. Try matching department
        if ($department) {
            $match = $subsidiaries->first(function ($sub) use ($department) {
                return strcasecmp($sub->name, $department) === 0
                    || strcasecmp($sub->code, $department) === 0
                    || str_contains(strtolower($department), strtolower($sub->code ?? ''));
            });
            if ($match) return $match->id;
        }

        // 2. Try matching companyName
        if ($companyName) {
            $match = $subsidiaries->first(function ($sub) use ($companyName) {
                return strcasecmp($sub->name, $companyName) === 0
                    || str_contains(strtolower($companyName), strtolower($sub->name ?? ''));
            });
            if ($match) return $match->id;
        }

        // 3. Match email domain (e.g., gsoptimize.lk -> George Steuart Optimize)
        $emailDomain = strtolower(substr($email, strrpos($email, '@') + 1));
        $match = $subsidiaries->first(function ($sub) use ($emailDomain) {
            $subCode = strtolower($sub->code ?? '');
            $subName = strtolower($sub->name ?? '');
            return str_contains($emailDomain, $subCode)
                || str_contains($subName, explode('.', $emailDomain)[0] ?? '');
        });
        if ($match) return $match->id;

        // Fallback to default
        return $subsidiaries->first()?->id;
    }
}
