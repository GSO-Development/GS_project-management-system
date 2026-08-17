<?php

namespace App\Livewire;

use App\Models\ActivityLog;
use App\Models\Subsidiary;
use App\Models\User;
use App\Services\AzureGraphService;
use Illuminate\Support\Facades\Hash;
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
            'email'         => 'required|email|max:255|unique:users,email,' . ($this->editingId ?? 'NULL'),
            'password'      => 'nullable|min:8',
            'phone_number'  => 'nullable|string|max:50',
            'subsidiary_id' => 'nullable',
            'role'          => 'required|in:super_admin,regular_user',
            'is_active'     => 'boolean',
        ];
    }

    public function updatedSearch() { $this->resetPage(); }
    public function updatedRoleFilter() { $this->resetPage(); }
    public function updatedSubsidiaryFilter() { $this->resetPage(); }
    public function updatedStatusFilter() { $this->resetPage(); }

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
            'editingId', 'name', 'email', 'password', 'phone_number',
            'subsidiary_id', 'role', 'is_active',
            'creationType', 'azureSearchQuery', 'azureSearchResults', 'selectedAzureUser',
        ]);

        $this->role      = 'regular_user';
        $this->is_active = true;
        $this->creationType = 'system';

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

        $this->editingId      = $user->id;
        $this->name           = $user->name;
        $this->email          = $user->email;
        $this->password       = '';
        $this->phone_number   = $user->phone_number;
        $this->subsidiary_id  = $user->subsidiary_id;
        $this->role           = $user->hasRole('super_admin') ? 'super_admin' : 'regular_user';
        $this->is_active      = (bool) $user->is_active;
        $this->creationType   = 'system'; // Edit always uses system form
        $this->selectedAzureUser = null;

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

            $existingUser = User::where('azure_id', $azureId)->first()
                ?? User::where('email', $this->email)->first();

            $targetRoles = ($this->role === 'super_admin') ? ['super_admin'] : [];
            if ($existingUser) {
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
                $data['password'] = Hash::make('Password@123');
            }

            if ($this->editingId) {
                $user = User::findOrFail($this->editingId);
                $user->update($data);
                $action = 'updated_user';
            } else {
                $data['must_change_password'] = false;
                $user = User::create($data);
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
        \App\Models\Project::where('project_manager_id', $user->id)->update(['project_manager_id' => null]);
        \App\Models\Project::where('created_by', $user->id)->update(['created_by' => null]);
        \App\Models\WbsItem::where('assigned_user_id', $user->id)->update(['assigned_user_id' => null]);
        \App\Models\ProjectRisk::where('owner_id', $user->id)->update(['owner_id' => null]);
        \App\Models\ProjectStatusUpdate::where('created_by', $user->id)->update(['created_by' => null]);
        \App\Models\ProjectDocument::where('uploaded_by', $user->id)->update(['uploaded_by' => null]);
        $user->projects()->detach();
        $user->forceDelete();

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
