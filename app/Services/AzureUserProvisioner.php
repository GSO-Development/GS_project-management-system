<?php

namespace App\Services;

use App\Models\ActivityLog;
use App\Models\Subsidiary;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use RuntimeException;

/**
 * Provisions a local system user from a Microsoft Azure AD (Entra ID) directory entry.
 *
 * Used when a PMO Admin picks someone from an Azure AD search who does not yet exist
 * in the system (e.g. while building the project team on the "Create Project" page).
 * The created account is SSO-only (random password) and immediately shows up on the
 * Participants page, exactly like users provisioned from the Participants screen.
 */
class AzureUserProvisioner
{
    /**
     * Find or create the local user that matches the given (normalized) Azure AD user.
     *
     * @param  array<string,mixed>  $azureUser  A row returned by AzureGraphService::searchUsers().
     * @return array{user: User, created: bool}
     *
     * @throws RuntimeException When the user cannot be used (invalid data, deactivated, admin account).
     */
    public static function provision(array $azureUser, ?int $fallbackSubsidiaryId = null): array
    {
        $azureId = trim((string) ($azureUser['id'] ?? $azureUser['azure_id'] ?? ''));
        $email   = strtolower(trim((string) ($azureUser['mail'] ?? $azureUser['email'] ?? '')));
        $name    = trim((string) ($azureUser['displayName'] ?? $azureUser['name'] ?? ''));

        if ($azureId === '' || $email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new RuntimeException('This Azure account has no valid e-mail address, so it cannot be added.');
        }

        $existing = User::withTrashed()->where('azure_id', $azureId)->first()
            ?? User::withTrashed()->where('email', $email)->first();

        if ($existing) {
            if ($existing->trashed()) {
                $existing->restore();
                $existing->forceFill(['is_active' => true])->save();
            } elseif (!$existing->is_active) {
                throw new RuntimeException(
                    "{$existing->name} is deactivated in the system. Activate the account from the Participants page first."
                );
            }

            if ($existing->isGovernanceOrAdminUser()) {
                throw new RuntimeException(
                    "{$existing->name} is a PMO / Admin account and cannot be assigned to a project role."
                );
            }

            // Link the Azure identity if this local account was created manually.
            if (empty($existing->azure_id)) {
                $existing->forceFill(['azure_id' => $azureId])->save();
            }

            return ['user' => $existing, 'created' => false];
        }

        $subsidiaryId = self::resolveSubsidiaryId($azureUser) ?? $fallbackSubsidiaryId;

        $user = AuditLogService::withoutObserver(function () use ($name, $email, $azureId, $azureUser, $subsidiaryId) {
            return User::create([
                'name'                 => $name !== '' ? $name : $email,
                'email'                => $email,
                'azure_id'             => $azureId,
                'password'             => Hash::make(str()->random(32)), // SSO only
                'phone_number'         => $azureUser['phone'] ?? $azureUser['mobilePhone'] ?? null,
                'subsidiary_id'        => $subsidiaryId,
                'is_active'            => true,
                'must_change_password' => false,
            ]);
        });

        AuditLogService::log(
            action: 'provisioned_azure_user',
            module: 'users',
            record: $user,
            new: ['name' => $user->name, 'email' => $user->email, 'source' => 'project_create_azure_search'],
            actorType: ActivityLog::ACTOR_USER
        );

        return ['user' => $user, 'created' => true];
    }

    /**
     * Match an Azure AD user to a local subsidiary.
     * Priority: department → companyName → e-mail domain.
     */
    public static function resolveSubsidiaryId(array $azureUser): ?int
    {
        $subsidiaries = Subsidiary::orderBy('name')->get();
        $department   = $azureUser['department'] ?? null;
        $company      = $azureUser['company'] ?? $azureUser['companyName'] ?? null;
        $email        = $azureUser['mail'] ?? $azureUser['email'] ?? '';

        if ($department) {
            $match = $subsidiaries->first(function ($sub) use ($department) {
                return strcasecmp($sub->name, $department) === 0
                    || strcasecmp($sub->code, $department) === 0
                    || ($sub->code && stripos($department, $sub->code) !== false)
                    || ($sub->name && stripos($department, $sub->name) !== false);
            });
            if ($match) {
                return $match->id;
            }
        }

        if ($company) {
            $match = $subsidiaries->first(function ($sub) use ($company) {
                return strcasecmp($sub->name, $company) === 0
                    || ($sub->name && stripos($company, $sub->name) !== false)
                    || ($sub->code && stripos($company, $sub->code) !== false);
            });
            if ($match) {
                return $match->id;
            }
        }

        if ($email && str_contains($email, '@')) {
            $domain       = strtolower(substr($email, strrpos($email, '@') + 1));
            $domainPrefix = explode('.', $domain)[0];

            $match = $subsidiaries->first(function ($sub) use ($domain, $domainPrefix) {
                $code = strtolower($sub->code ?? '');
                $name = str_replace(' ', '', strtolower($sub->name ?? ''));

                return ($code !== '' && (str_contains($domainPrefix, $code) || str_contains($domain, $code)))
                    || ($name !== '' && str_contains($domainPrefix, $name));
            });
            if ($match) {
                return $match->id;
            }
        }

        return null;
    }
}

