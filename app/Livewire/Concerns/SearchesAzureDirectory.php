<?php

namespace App\Livewire\Concerns;

use App\Models\User;
use App\Services\AzureGraphService;
use App\Services\AzureUserProvisioner;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\Locked;

/**
 * Lets a Livewire component search Microsoft Azure AD while the user types in a
 * people-picker and add the chosen person (creating the local account if needed)
 * straight into the role that picker belongs to.
 *
 * Used by the "Project Team & Collaborators" dialog and the project reassign screens,
 * mirroring what the Create Project page already does.
 *
 * The host component must implement:
 *   - canUseAzureDirectory(): bool                   who may search / provision
 *   - azureSubsidiaryId(): ?int                      fallback subsidiary for new accounts
 *   - azureIsValidRole(string $role): bool           which picker keys are supported
 *   - azureRoleLabel(string $role): string           friendly role name for toasts
 *   - azureAssignToRole(string $role, User $user): ?string
 *         put the user into the role; return an error message on failure, null on success
 *
 * Picker keys typed into `azureSearch.{key}` (or a custom hook calling azureAutoSearch()).
 */
trait SearchesAzureDirectory
{
    /** Picker the Azure panel is currently shown for (pm | sponsor | owner | steering | member | collab ...). */
    #[Locked]
    public ?string $azureRole = null;

    #[Locked]
    public string $azureQuery = '';

    #[Locked]
    public ?string $azureError = null;

    /** @var array<int, array<string, mixed>> */
    #[Locked]
    public array $azureResults = [];

    /** @var array<int, int> Users provisioned/picked from Azure during this session, kept visible in every picker. */
    #[Locked]
    public array $azureAddedUserIds = [];

    /** Text typed into each picker's search box, keyed by picker key. */
    public array $azureSearch = [];

    abstract protected function canUseAzureDirectory(): bool;

    abstract protected function azureSubsidiaryId(): ?int;

    abstract protected function azureIsValidRole(string $role): bool;

    abstract protected function azureRoleLabel(string $role): string;

    /** @return string|null error message, or null when the user was assigned. */
    abstract protected function azureAssignToRole(string $role, User $user): ?string;

    protected function azureIsConfigured(): bool
    {
        return !empty(config('services.azure.tenant'))
            && !empty(config('services.azure.client_id'))
            && !empty(config('services.azure.client_secret'));
    }

    /** Livewire hook: fires for `azureSearch.{key}` inputs. */
    public function updatedAzureSearch($value, $key = null): void
    {
        if (is_string($key) && $key !== '') {
            $this->azureAutoSearch($key, (string) $value);
        }
    }

    protected function azureAutoSearch(string $role, string $text): void
    {
        if (!$this->canUseAzureDirectory() || !$this->azureIsValidRole($role)) {
            return;
        }

        $this->azureRole    = $role;
        $this->azureQuery   = trim($text);
        $this->azureResults = [];
        $this->azureError   = null;

        $this->runAzureSearch();
    }

    public function closeAzureSearch(): void
    {
        $this->azureRole    = null;
        $this->azureQuery   = '';
        $this->azureResults = [];
        $this->azureError   = null;
    }

    /** Clear every search box and any open Azure panel (call when a dialog opens/closes). */
    protected function resetAzureSearchState(): void
    {
        $this->azureSearch = [];
        $this->closeAzureSearch();
    }

    protected function runAzureSearch(): void
    {
        $this->azureError   = null;
        $this->azureResults = [];

        $query = trim($this->azureQuery);
        if (!$this->canUseAzureDirectory() || mb_strlen($query) < 2) {
            return;
        }

        if (!$this->azureIsConfigured()) {
            $this->azureError = 'Azure AD is not configured. Please set the Azure tenant / client credentials first.';
            return;
        }

        try {
            $results = AzureGraphService::searchUsers($query, 8);
        } catch (\Throwable $e) {
            Log::error(static::class . ': Azure user search failed - ' . $e->getMessage());
            $this->azureError = 'Could not reach Azure AD right now. Please try again.';
            return;
        }

        if (empty($results)) {
            return;
        }

        $azureIds = array_values(array_filter(array_column($results, 'id')));
        $emails   = array_values(array_filter(array_column($results, 'email')));

        $existing = User::withTrashed()
            ->where(function ($q) use ($azureIds, $emails) {
                $q->whereIn('azure_id', $azureIds)->orWhereIn('email', $emails);
            })
            ->get();

        $rows = [];
        foreach ($results as $row) {
            $match = $existing->first(function ($u) use ($row) {
                return (!empty($u->azure_id) && $u->azure_id === ($row['id'] ?? null))
                    || strcasecmp((string) $u->email, (string) ($row['email'] ?? '')) === 0;
            });

            $row['in_system']      = (bool) $match;
            $row['blocked_reason'] = null;

            if ($match && !$match->trashed()) {
                if (!$match->is_active) {
                    $row['blocked_reason'] = 'Deactivated';
                } elseif ($match->isGovernanceOrAdminUser()) {
                    $row['blocked_reason'] = 'Admin account';
                }
            }

            $rows[] = $row;
        }

        $this->azureResults = $rows;
    }

    /**
     * Add the chosen Azure result to the system (if new) and assign it to the active picker's role.
     */
    public function addAzureUser(int $index): void
    {
        if (!$this->canUseAzureDirectory()) {
            $this->dispatch('toast', message: 'You are not allowed to add users from Azure AD.', type: 'error');
            return;
        }

        $role      = $this->azureRole;
        $azureUser = $this->azureResults[$index] ?? null;

        if (!$role || !$azureUser || !$this->azureIsValidRole($role)) {
            return;
        }

        try {
            ['user' => $user, 'created' => $created] = AzureUserProvisioner::provision($azureUser, $this->azureSubsidiaryId());
        } catch (\RuntimeException $e) {
            $this->azureError = $e->getMessage();
            return;
        } catch (\Throwable $e) {
            Log::error(static::class . ': Azure user provisioning failed - ' . $e->getMessage());
            $this->azureError = 'Could not add this person. Please try again.';
            return;
        }

        // Keep the person visible in every picker, even if their subsidiary differs from the project's.
        $this->azureAddedUserIds = array_values(array_unique(array_merge($this->azureAddedUserIds, [(int) $user->id])));

        $error = $this->azureAssignToRole($role, $user);
        if ($error !== null) {
            $this->azureError = $error;
            return;
        }

        $label = $this->azureRoleLabel($role);
        $this->azureSearch[$role] = '';
        $this->closeAzureSearch();

        $this->dispatch(
            'toast',
            message: $created
                ? "{$user->name} was added to the system from Azure AD and assigned as {$label}."
                : "{$user->name} assigned as {$label}.",
            type: 'success'
        );
    }

    /**
     * Pick someone who is already in the system from a picker's dropdown (no Azure lookup needed).
     */
    public function pickLocalUser(string $role, int $userId): void
    {
        if (!$this->azureIsValidRole($role)) {
            return;
        }

        $user = User::where('is_active', true)->find($userId);
        if (!$user || $user->isGovernanceOrAdminUser()) {
            return;
        }

        $error = $this->azureAssignToRole($role, $user);
        if ($error !== null) {
            $this->dispatch('toast', message: $error, type: 'error');
            return;
        }

        $this->azureSearch[$role] = '';
        $this->closeAzureSearch();
    }

    /** Users picked via Azure this session, to merge into a picker's visible list. */
    protected function azureAddedUsers()
    {
        return empty($this->azureAddedUserIds)
            ? collect()
            : User::with(['subsidiary', 'roles'])->whereIn('id', $this->azureAddedUserIds)->get();
    }
}

