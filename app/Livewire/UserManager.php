<?php

namespace App\Livewire;

use App\Models\ActivityLog;
use App\Models\Subsidiary;
use App\Models\User;
use App\Services\AzureGraphService;
use App\Services\PmoAdminGuard;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Livewire\WithPagination;
use Spatie\Permission\Models\Role;

class UserManager extends Component
{
    use WithPagination;

    // Filters
    public string $search = '';
    public string $roleFilter = 'all';
    public string $subsidiaryFilter = 'all';
    public string $statusFilter = 'all';

    // Modal state
    public bool $showModal = false;
    public ?int $editingId = null;
    public bool $isEditingLastAdmin = false;

    // Creation type: 'system' | 'azure'
    public string $creationType = 'system';

    // Azure search state
    public string $azureSearchQuery = '';
    public array $azureSearchResults = [];
    public bool $azureSearchLoading = false;
    public ?array $selectedAzureUser = null;

    // Shared form fields
    public string $name = '';
    public string $email = '';
    public string $password = '';
    public ?string $phone_number = null;
    public ?int $subsidiary_id = null;
    public string $role = 'regular_user';
    public bool $is_active = true;

    protected function rules(): array
    {
        if ($this->creationType === 'azure' && $this->selectedAzureUser) {
            return [
                'role'          => 'required|in:super_admin,regular_user',
                'subsidiary_id' => 'nullable',
                'is_active'     => 'boolean',
            ];
        }

        return [
            'name'          => 'required|string|max:255',
            'email'         => ['required', 'email', 'max:255', Rule::unique('users', 'email')->whereNull('deleted_at')->ignore($this->editingId)],
            'password'      => 'nullable|min:8',
            'phone_number'  => 'nullable|string|max:50',
            'subsidiary_id' => 'nullable',
            'role'          => 'required|in:super_admin,regular_user',
            'is_active'     => 'boolean',
        ];
    }

    // Batch Selection State
    public array $selectedUsers = [];
    public bool $selectAll = false;

    public function updatedSearch() { $this->resetPage(); $this->clearSelection(); }
    public function updatedRoleFilter() { $this->resetPage(); $this->clearSelection(); }
    public function updatedSubsidiaryFilter() { $this->resetPage(); $this->clearSelection(); }
    public function updatedStatusFilter() { $this->resetPage(); $this->clearSelection(); }

    public function updatedSelectAll($value): void
    {
        if ($value) {
            $q = User::query();
            if ($this->search) {
                $q->where(fn($sq) => $sq->where('name', 'like', "%{$this->search}%")
                    ->orWhere('email', 'like', "%{$this->search}%"));
            }
            if ($this->roleFilter === 'super_admin') {
                $q->role('super_admin');
            } elseif ($this->roleFilter === 'regular_user') {
                $q->whereDoesntHave('roles', fn($rq) => $rq->where('name', 'super_admin'));
            }
            if ($this->subsidiaryFilter !== 'all') {
                $q->where('subsidiary_id', $this->subsidiaryFilter);
            }
            if ($this->statusFilter !== 'all') {
                $q->where('is_active', $this->statusFilter === 'active');
            }
            $this->selectedUsers = $q->pluck('id')->map(fn($id) => (string)$id)->toArray();
        } else {
            $this->selectedUsers = [];
        }
    }

    public function clearSelection(): void
    {
        $this->selectedUsers = [];
        $this->selectAll = false;
    }

    public function batchActivate(): void
    {
        if (!$this->isAuthorized() || empty($this->selectedUsers)) return;

        User::whereIn('id', $this->selectedUsers)->update(['is_active' => true]);
        $count = count($this->selectedUsers);
        $this->clearSelection();
        $this->dispatch('toast', message: "{$count} user(s) activated successfully.", type: 'success');
    }

    public function batchDisable(): void
    {
        if (!$this->isAuthorized() || empty($this->selectedUsers)) return;

        $targetIds = array_diff($this->selectedUsers, [(string)auth()->id(), auth()->id()]);
        $partition = PmoAdminGuard::partitionBatch(array_values($targetIds));
        $safeIds   = $partition['safe'];
        $blockedCount = count($partition['blocked']);

        User::whereIn('id', $safeIds)->update(['is_active' => false]);
        $count = count($safeIds);
        $this->clearSelection();

        if ($blockedCount > 0 && $count === 0) {
            $this->dispatch('toast', message: 'Cannot disable the only remaining PMO Admin. At least one PMO Admin must remain active.', type: 'error');
        } elseif ($blockedCount > 0) {
            $this->dispatch('toast', message: "{$count} user(s) disabled. The last PMO Admin was protected.", type: 'info');
        } else {
            $this->dispatch('toast', message: "{$count} user(s) disabled successfully.", type: 'warning');
        }
    }

    public function batchMakeAdmin(): void
    {
        if (!$this->isAuthorized() || empty($this->selectedUsers)) return;

        $users = User::whereIn('id', $this->selectedUsers)->get();
        foreach ($users as $u) {
            $u->assignRole('super_admin');
        }
        $count = count($users);
        $this->clearSelection();
        $this->dispatch('toast', message: "{$count} user(s) assigned PMO Admin role.", type: 'success');
    }

    public function batchMakeUser(): void
    {
        if (!$this->isAuthorized() || empty($this->selectedUsers)) return;

        $partition    = PmoAdminGuard::partitionBatch($this->selectedUsers);
        $safeIds      = $partition['safe'];
        $blockedCount = count($partition['blocked']);
        $count        = 0;

        foreach ($safeIds as $id) {
            $u = User::find($id);
            if (!$u) continue;
            $u->removeRole('super_admin');
            $u->removeRole('pmo_admin');
            $count++;
        }

        $this->clearSelection();

        if ($blockedCount > 0 && $count === 0) {
            $this->dispatch('toast', message: 'Cannot demote the only remaining PMO Admin. At least one PMO Admin must exist in the system.', type: 'error');
        } elseif ($blockedCount > 0) {
            $this->dispatch('toast', message: "{$count} user(s) set to Regular User. The last PMO Admin was protected.", type: 'info');
        } else {
            $this->dispatch('toast', message: "{$count} user(s) set to Regular User role.", type: 'success');
        }
    }

    public function batchDelete(): void
    {
        if (!$this->isAuthorized() || empty($this->selectedUsers)) return;

        // Remove self from selection
        $targetIds    = array_diff($this->selectedUsers, [(string) auth()->id(), auth()->id()]);
        $partition    = PmoAdminGuard::partitionBatch(array_values($targetIds));
        $safeIds      = $partition['safe'];
        $blockedCount = count($partition['blocked']);
        $count        = 0;

        foreach ($safeIds as $id) {
            $user = User::find($id);
            if (!$user) continue;
            \Illuminate\Support\Facades\DB::transaction(function () use ($user) {
                \App\Models\Project::where('project_manager_id', $user->id)->update(['project_manager_id' => null]);
                \App\Models\WbsItem::where('assigned_user_id', $user->id)->update(['assigned_user_id' => null]);
                \App\Models\ProjectRisk::where('owner_id', $user->id)->update(['owner_id' => null]);
                $user->projects()->detach();
                $user->is_active = false;
                $user->save();
                $user->delete();
            });
            $count++;
        }

        $this->clearSelection();

        if ($blockedCount > 0 && $count === 0) {
            $this->dispatch('toast', message: 'Cannot delete the only remaining PMO Admin. At least one PMO Admin must always exist in the system.', type: 'error');
        } elseif ($blockedCount > 0) {
            $this->dispatch('toast', message: "{$count} user(s) deleted. The last PMO Admin was protected from deletion.", type: 'info');
        } else {
            $this->dispatch('toast', message: "{$count} user(s) deleted successfully.", type: 'info');
        }
    }

    public function updatedCreationType(): void
    {
        // Reset azure state when toggling
        $this->azureSearchQuery   = '';
        $this->azureSearchResults = [];
        $this->selectedAzureUser  = null;
        $this->resetValidation();
    }

    public function isAuthorized(): bool
    {
        $user = auth()->user();
        if (!$user) return false;
        return $user->hasRole('super_admin');
    }

    public function updatedAzureSearchQuery(): void
    {
        // Auto-search as user types (needs at least 2 chars)
        if (strlen(trim($this->azureSearchQuery)) >= 2) {
            $this->searchAzureUsers();
        } else {
            $this->azureSearchResults = [];
        }
    }

    // ---------------------------------------------------------------
    // Azure AD Search
    // ---------------------------------------------------------------

    public function searchAzureUsers(): void
    {
        if (strlen(trim($this->azureSearchQuery)) < 2) {
            $this->azureSearchResults = [];
            return;
        }

        $this->azureSearchLoading = true;
        try {
            $this->azureSearchResults = AzureGraphService::searchUsers($this->azureSearchQuery, 8);
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::error('UserManager: searchAzureUsers exception - ' . $e->getMessage());
            $this->azureSearchResults = [];
        }
        $this->azureSearchLoading = false;
    }

    public function selectAzureUser(int $index): void
    {
        $user = $this->azureSearchResults[$index] ?? null;
        if (!$user) return;

        $this->selectedAzureUser  = $user;
        $this->name               = $user['displayName'] ?? $user['name'] ?? '';
        $this->email              = $user['mail'] ?? $user['email'] ?? '';
        $this->phone_number       = $user['phone'] ?? $user['mobilePhone'] ?? null;
        $this->azureSearchResults = [];
        $this->azureSearchQuery   = $user['displayName'] ?? $user['name'] ?? '';

        $existingUser = User::withTrashed()->where('azure_id', $user['id'] ?? '')->first()
            ?? User::withTrashed()->where('email', $this->email)->first();
        if ($existingUser) {
            $this->isEditingLastAdmin = PmoAdminGuard::isLastAdmin($existingUser);
            if ($existingUser->isPmoAdmin()) {
                $this->role = 'super_admin';
            }
        } else {
            $this->isEditingLastAdmin = false;
        }

        // Auto-resolve subsidiary from Azure AD department, company, or email domain
        $resolvedId = $this->resolveSubsidiaryFromAzureUser($user);
        if ($resolvedId) {
            $this->subsidiary_id = $resolvedId;
        }
    }

    /**
     * Match an Azure AD user to a local subsidiary.
     * Priority: department field → companyName → email domain.
     */
    private function resolveSubsidiaryFromAzureUser(array $azureUser): ?int
    {
        $subsidiaries = Subsidiary::orderBy('name')->get();
        $department   = $azureUser['department'] ?? null;
        $company      = $azureUser['company']     ?? null;
        $email        = $azureUser['mail']        ?? '';

        // 1. Match on Azure AD department field
        if ($department) {
            $match = $subsidiaries->first(function ($sub) use ($department) {
                return strcasecmp($sub->name, $department) === 0
                    || strcasecmp($sub->code, $department) === 0
                    || stripos($department, $sub->code) !== false
                    || stripos($department, $sub->name) !== false;
            });
            if ($match) return $match->id;
        }

        // 2. Match on Azure AD companyName field
        if ($company) {
            $match = $subsidiaries->first(function ($sub) use ($company) {
                return strcasecmp($sub->name, $company) === 0
                    || stripos($company, $sub->name) !== false
                    || stripos($company, $sub->code) !== false;
            });
            if ($match) return $match->id;
        }

        // 3. Match on email domain (e.g. lakshan@gsoptimize.lk → GSOPT)
        if ($email && str_contains($email, '@')) {
            $domain = strtolower(substr($email, strrpos($email, '@') + 1));
            // Strip .lk / .com / etc. to get domain prefix
            $domainPrefix = explode('.', $domain)[0]; // e.g. "gsoptimize"

            $match = $subsidiaries->first(function ($sub) use ($domain, $domainPrefix) {
                $code = strtolower($sub->code ?? '');
                $name = strtolower($sub->name ?? '');
                // Check if domain prefix contains the subsidiary code
                return str_contains($domainPrefix, $code)
                    || str_contains($domain, $code)
                    || str_contains($domainPrefix, str_replace(' ', '', $name));
            });
            if ($match) return $match->id;
        }

        return null; // No match — let admin choose
    }

    public function clearAzureSelection(): void
    {
        $this->selectedAzureUser  = null;
        $this->azureSearchQuery   = '';
        $this->azureSearchResults = [];
        $this->name               = '';
        $this->email              = '';
        $this->phone_number       = null;
        $this->isEditingLastAdmin = false;
    }

    // ---------------------------------------------------------------
    // Modal Management
    // ---------------------------------------------------------------

    public function openCreateModal(): void
    {
        if (!$this->isAuthorized()) {
            $this->dispatch('toast', message: 'Only PMO Admins can add new users.', type: 'error');
            return;
        }

        $this->reset([
            'editingId', 'isEditingLastAdmin', 'name', 'email', 'password', 'phone_number',
            'subsidiary_id', 'role', 'is_active',
            'creationType', 'azureSearchQuery', 'azureSearchResults', 'selectedAzureUser',
        ]);

        $this->role      = 'regular_user';
        $this->is_active = true;
        $this->creationType = 'system';
        $this->isEditingLastAdmin = false;

        $firstSub = Subsidiary::first();
        if ($firstSub) {
            $this->subsidiary_id = $firstSub->id;
        }

        $this->resetValidation();
        $this->showModal = true;
    }

    public function edit(User $user): void
    {
        if (!$this->isAuthorized()) {
            $this->dispatch('toast', message: 'Only authorized users can edit users.', type: 'error');
            return;
        }

        $this->editingId          = $user->id;
        $this->isEditingLastAdmin = PmoAdminGuard::isLastAdmin($user);
        $this->name               = $user->name;
        $this->email              = $user->email;
        $this->password           = '';
        $this->phone_number       = $user->phone_number;
        $this->subsidiary_id      = $user->subsidiary_id;
        $this->role               = $user->isPmoAdmin() ? 'super_admin' : 'regular_user';
        $this->is_active          = (bool) $user->is_active;
        $this->creationType       = 'system'; // Edit always uses system form
        $this->selectedAzureUser  = null;

        $this->resetValidation();
        $this->showModal = true;
    }

    // ---------------------------------------------------------------
    // Save
    // ---------------------------------------------------------------

    public function save(): void
    {
        if (!$this->isAuthorized()) {
            $this->dispatch('toast', message: 'Only authorized users can save users.', type: 'error');
            return;
        }

        if (empty($this->subsidiary_id) || $this->subsidiary_id === '0') {
            $this->subsidiary_id = null;
        } else {
            $this->subsidiary_id = (int) $this->subsidiary_id;
        }

        $this->validate();

        if ($this->creationType === 'azure' && $this->selectedAzureUser) {
            // --- Azure User provisioning ---
            $azureId = $this->selectedAzureUser['id'];

            $existingUser = User::withTrashed()->where('azure_id', $azureId)->first()
                ?? User::withTrashed()->where('email', $this->email)->first();

            $targetRoles = ($this->role === 'super_admin') ? ['super_admin'] : [];
            if ($existingUser) {
                // Guard: prevent demotion or deactivation of the last PMO Admin
                if (PmoAdminGuard::isLastAdmin($existingUser) && ($this->role !== 'super_admin' || !$this->is_active)) {
                    $this->dispatch('toast',
                        message: 'Cannot change the role or disable the only remaining PMO Admin in the system. Add another PMO Admin first.',
                        type: 'error'
                    );
                    return;
                }
                if ($existingUser->trashed()) {
                    $existingUser->restore();
                }
                // Update existing user
                $existingUser->update([
                    'azure_id'      => $azureId,
                    'name'          => $this->name,
                    'phone_number'  => $this->phone_number ?? $existingUser->phone_number,
                    'subsidiary_id' => $this->subsidiary_id,
                    'is_active'     => (bool) $this->is_active,
                ]);
                $existingUser->syncRoles($targetRoles);
                $user = $existingUser;
                $action = 'updated_user';
            } else {
                // Create new Azure-provisioned user (no password — SSO only)
                $user = User::create([
                    'name'                 => $this->name,
                    'email'                => $this->email,
                    'azure_id'             => $azureId,
                    'password'             => Hash::make(str()->random(32)),
                    'phone_number'         => $this->phone_number,
                    'subsidiary_id'        => $this->subsidiary_id,
                    'is_active'            => (bool) $this->is_active,
                    'must_change_password' => false,
                    'email_verified_at'    => now(),
                ]);
                $user->syncRoles($targetRoles);
                $action = 'provisioned_azure_user';
            }

        } else {
            // --- Local System user create/update ---
            $data = [
                'name'          => trim($this->name),
                'email'         => trim($this->email),
                'phone_number'  => $this->phone_number ? trim($this->phone_number) : null,
                'subsidiary_id' => $this->subsidiary_id,
                'is_active'     => (bool) $this->is_active,
            ];

            if (!empty($this->password)) {
                $data['password'] = Hash::make($this->password);
            } elseif (!$this->editingId) {
                $data['password'] = Hash::make(config('app.super_admin_password', 'Password@123'));
            }

            if ($this->editingId) {
                $user = User::findOrFail($this->editingId);

                // Guard: prevent demotion or deactivation of the last PMO Admin
                if (PmoAdminGuard::isLastAdmin($user) && ($this->role !== 'super_admin' || !$this->is_active)) {
                    $this->dispatch('toast',
                        message: 'Cannot change the role or disable the only remaining PMO Admin in the system. Add another PMO Admin first.',
                        type: 'error'
                    );
                    return;
                }

                $user->update($data);
                $action = 'updated_user';
            } else {
                $trashedUser = User::onlyTrashed()->where('email', trim($this->email))->first();
                if ($trashedUser) {
                    $trashedUser->restore();
                    $data['must_change_password'] = false;
                    $trashedUser->update($data);
                    $user = $trashedUser;
                } else {
                    $data['must_change_password'] = true;
                    $user = User::create($data);
                }
                $action = 'created_user';
            }

            $targetRoles = ($this->role === 'super_admin') ? ['super_admin'] : [];
            $user->syncRoles($targetRoles);
        }

        ActivityLog::create([
            'user_id'     => auth()->id(),
            'action'      => $action,
            'module'      => 'users',
            'record_type' => User::class,
            'record_id'   => $user->id,
            'new_values'  => ['name' => $user->name, 'email' => $user->email, 'role' => $this->role],
        ]);

        $this->showModal = false;
        $this->dispatch('toast', message: 'User saved successfully!', type: 'success');
    }

    // ---------------------------------------------------------------
    // Status & Delete
    // ---------------------------------------------------------------

    public function toggleStatus(int $userId): void
    {
        if (!$this->isAuthorized()) {
            $this->dispatch('toast', message: 'Unauthorized.', type: 'error');
            return;
        }

        $user = User::findOrFail($userId);

        // Guard: prevent disabling the last active PMO Admin
        if ($user->is_active && PmoAdminGuard::isLastAdmin($user)) {
            $this->dispatch('toast',
                message: 'Cannot disable the only remaining PMO Admin. At least one active PMO Admin must always exist in the system.',
                type: 'error'
            );
            return;
        }

        $user->is_active = !$user->is_active;
        $user->save();

        $this->dispatch('toast', message: 'Account status updated to ' . ($user->is_active ? 'Active' : 'Disabled'), type: 'success');
    }

    public function deleteUser(int $id): void
    {
        if (!$this->isAuthorized()) {
            $this->dispatch('toast', message: 'Unauthorized.', type: 'error');
            return;
        }

        if ($id === auth()->id()) {
            $this->dispatch('toast', message: 'Cannot delete your own account.', type: 'error');
            return;
        }

        $user = User::findOrFail($id);

        // Guard: prevent deletion of the last PMO Admin
        if (PmoAdminGuard::isLastAdmin($user)) {
            $this->dispatch('toast',
                message: 'Cannot delete the only remaining PMO Admin. Add another PMO Admin first.',
                type: 'error'
            );
            return;
        }

        \Illuminate\Support\Facades\DB::transaction(function () use ($user) {
            // 1. Unassign from active projects where this user is the Project Manager
            \App\Models\Project::where('project_manager_id', $user->id)->update(['project_manager_id' => null]);

            // 2. Unassign from active/pending deliverables & tasks
            \App\Models\WbsItem::where('assigned_user_id', $user->id)->update(['assigned_user_id' => null]);

            // 3. Clear risk ownership
            \App\Models\ProjectRisk::where('owner_id', $user->id)->update(['owner_id' => null]);

            // 4. Detach from project team memberships
            $user->projects()->detach();

            // 5. Deactivate and soft delete
            // Note: User model uses SoftDeletes. Historical audit records (project_status_updates.created_by,
            // project_documents.uploaded_by, projects.created_by, comments) are non-nullable and must remain
            // intact for compliance. Soft-deleting immediately removes the user from active listings and logins.
            $user->is_active = false;
            $user->save();
            $user->delete();
        });

        $this->dispatch('toast', message: 'User deleted successfully.', type: 'info');
    }

    // ---------------------------------------------------------------
    // Render
    // ---------------------------------------------------------------

    public function render()
    {
        $baseQuery = function () {
            $q = User::with(['subsidiary', 'roles']);

            if ($this->search) {
                $q->where(fn($sq) => $sq->where('name', 'like', "%{$this->search}%")
                    ->orWhere('email', 'like', "%{$this->search}%"));
            }
            if ($this->roleFilter === 'super_admin') {
                $q->role('super_admin');
            } elseif ($this->roleFilter === 'regular_user') {
                $q->whereDoesntHave('roles', fn($rq) => $rq->where('name', 'super_admin'));
            }
            if ($this->subsidiaryFilter !== 'all') {
                $q->where('subsidiary_id', $this->subsidiaryFilter);
            }
            if ($this->statusFilter !== 'all') {
                $q->where('is_active', $this->statusFilter === 'active');
            }
            return $q;
        };

        $allFilteredUsers = $baseQuery()->orderBy('name')->get();
        $subsidiaries     = Subsidiary::orderBy('name')->get();
        $roles            = Role::orderBy('name')->get();

        $usersBySubsidiary = $subsidiaries->map(function ($sub) use ($allFilteredUsers) {
            return [
                'subsidiary' => $sub,
                'users'      => $allFilteredUsers->where('subsidiary_id', $sub->id)->values(),
            ];
        })->filter(fn($group) => $group['users']->isNotEmpty())->values();

        $noSubUsers = $allFilteredUsers->whereNull('subsidiary_id')->values();

        $users = $baseQuery()->latest()->paginate(25);

        return view('livewire.user-manager', compact(
            'users',
            'subsidiaries',
            'roles',
            'usersBySubsidiary',
            'noSubUsers'
        ));
    }
}
