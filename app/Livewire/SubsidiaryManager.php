<?php

namespace App\Livewire;

use App\Models\ActivityLog;
use App\Models\Project;
use App\Models\Subsidiary;
use App\Models\User;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

class SubsidiaryManager extends Component
{
    use WithPagination, WithFileUploads;

    public string $search = '';
    public string $statusFilter = 'all';
    public string $sortBy = 'created_at';
    public int $perPage = 10;
    public bool $showModal = false;
    public ?int $editingId = null;

    // Creation mode: 'system' | 'azure'
    public string $creationType = 'system';

    // Azure subsidiary search state
    public string $azureSearchQuery = '';
    public array $azureSearchResults = [];
    public bool $azureSearchLoading = false;
    public ?array $selectedAzureSub = null;
    public ?string $azure_id = null;

    // Form fields
    public string $code = '';
    public string $name = '';
    public ?string $description = null;
    public ?string $address = null;
    public ?string $contact_email = null;
    public ?string $contact_phone = null;
    public string $status = 'active';
    public $logoFile = null;

    // Checkbox selection state
    public array $selectedSubsidiaries = [];
    public bool $selectAll = false;

    // Delete modal state
    public bool $showDeleteModal = false;
    public ?int $subsidiaryToDeleteId = null;
    public ?string $subsidiaryToDeleteName = null;
    public ?string $subsidiaryToDeleteCode = null;
    public int $subsidiaryToDeleteProjectsCount = 0;
    public int $subsidiaryToDeleteUsersCount = 0;

    protected function rules(): array
    {
        return [
            'code' => 'required|string|max:20|unique:subsidiaries,code,' . $this->editingId,
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'address' => 'nullable|string',
            'contact_email' => 'nullable|email|max:255',
            'contact_phone' => 'nullable|string|max:50',
            'status' => 'required|in:active,inactive,archived',
            'logoFile' => 'nullable|image|max:2048',
        ];
    }

    public function updatedSearch() { $this->resetPage(); $this->selectedSubsidiaries = []; $this->selectAll = false; }
    public function updatedStatusFilter() { $this->resetPage(); $this->selectedSubsidiaries = []; $this->selectAll = false; }
    public function updatedSortBy() { $this->resetPage(); $this->selectedSubsidiaries = []; $this->selectAll = false; }

    public function updatedSelectAll($value): void
    {
        if ($value) {
            $this->selectedSubsidiaries = $this->getFilteredQuery()->pluck('id')->map(fn($id) => (string)$id)->toArray();
        } else {
            $this->selectedSubsidiaries = [];
        }
    }

    public function updatedSelectedSubsidiaries(): void
    {
        $this->selectedSubsidiaries = array_values(array_map('strval', $this->selectedSubsidiaries));
        $allIds = $this->getFilteredQuery()->pluck('id')->map(fn($id) => (string)$id)->toArray();
        if (empty($allIds)) {
            $this->selectAll = false;
            return;
        }
        $this->selectAll = empty(array_diff($allIds, $this->selectedSubsidiaries));
    }

    protected function getFilteredQuery()
    {
        $query = Subsidiary::query();

        if ($this->search) {
            $query->where(fn($q) => $q->where('name', 'like', "%{$this->search}%")->orWhere('code', 'like', "%{$this->search}%"));
        }

        if ($this->statusFilter !== 'all') {
            $query->where('status', $this->statusFilter);
        }

        if ($this->sortBy === 'name') {
            $query->orderBy('name', 'asc')->orderBy('id', 'asc');
        } elseif ($this->sortBy === 'code') {
            $query->orderBy('code', 'asc')->orderBy('id', 'asc');
        } else {
            $query->orderBy('created_at', 'desc')->orderBy('id', 'desc');
        }

        return $query;
    }

    protected function getCurrentPageSubsidiaryIds(): array
    {
        return $this->getFilteredQuery()
            ->paginate($this->perPage, ['id'], 'page', $this->getPage())
            ->pluck('id')
            ->map(fn($id) => (string)$id)
            ->toArray();
    }

    public function confirmDelete(int $id): void
    {
        if (!$this->isAuthorized()) {
            $this->dispatch('toast', message: 'Only PMO Admins can delete subsidiaries.', type: 'error');
            return;
        }

        $sub = Subsidiary::withCount(['projects', 'users'])->findOrFail($id);
        $this->subsidiaryToDeleteId = $sub->id;
        $this->subsidiaryToDeleteName = $sub->name;
        $this->subsidiaryToDeleteCode = $sub->code;
        $this->subsidiaryToDeleteProjectsCount = $sub->projects_count;
        $this->subsidiaryToDeleteUsersCount = $sub->users_count;
        $this->showDeleteModal = true;
    }

    public function cancelDelete(): void
    {
        $this->showDeleteModal = false;
        $this->subsidiaryToDeleteId = null;
        $this->subsidiaryToDeleteName = null;
        $this->subsidiaryToDeleteCode = null;
        $this->subsidiaryToDeleteProjectsCount = 0;
        $this->subsidiaryToDeleteUsersCount = 0;
    }

    public function deleteSubsidiary(): void
    {
        if (!$this->isAuthorized()) {
            $this->dispatch('toast', message: 'Only PMO Admins can delete subsidiaries.', type: 'error');
            return;
        }

        if (!$this->subsidiaryToDeleteId) {
            return;
        }

        $sub = Subsidiary::withCount(['projects', 'users'])->find($this->subsidiaryToDeleteId);
        if (!$sub) {
            $this->cancelDelete();
            return;
        }

        if ($sub->projects_count > 0 || $sub->users_count > 0) {
            $this->dispatch('toast', message: "Cannot delete '{$sub->name}' because it has {$sub->projects_count} project(s) and {$sub->users_count} user(s) assigned. Please reassign them first.", type: 'error');
            return;
        }

        $name = $sub->name;
        $code = $sub->code;

        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'deleted_subsidiary',
            'module' => 'subsidiaries',
            'record_type' => Subsidiary::class,
            'record_id' => $sub->id,
            'old_values' => $sub->toArray(),
        ]);

        $sub->delete();

        $this->cancelDelete();
        $this->selectedSubsidiaries = array_values(array_diff($this->selectedSubsidiaries, [(string)$sub->id]));
        $this->dispatch('toast', message: "Subsidiary [{$code}] '{$name}' deleted successfully!", type: 'success');
    }

    public function deleteSelected(): void
    {
        if (!$this->isAuthorized()) {
            $this->dispatch('toast', message: 'Only PMO Admins can delete subsidiaries.', type: 'error');
            return;
        }

        if (empty($this->selectedSubsidiaries)) {
            return;
        }

        $deletedCount = 0;
        $skippedCount = 0;

        foreach ($this->selectedSubsidiaries as $subId) {
            $sub = Subsidiary::withCount(['projects', 'users'])->find($subId);
            if (!$sub) continue;

            if ($sub->projects_count > 0 || $sub->users_count > 0) {
                $skippedCount++;
                continue;
            }

            ActivityLog::create([
                'user_id' => auth()->id(),
                'action' => 'deleted_subsidiary',
                'module' => 'subsidiaries',
                'record_type' => Subsidiary::class,
                'record_id' => $sub->id,
                'old_values' => $sub->toArray(),
            ]);

            $sub->delete();
            $deletedCount++;
        }

        $this->selectedSubsidiaries = [];
        $this->selectAll = false;

        if ($deletedCount > 0 && $skippedCount === 0) {
            $this->dispatch('toast', message: "Successfully deleted {$deletedCount} selected subsidiary(ies).", type: 'success');
        } elseif ($deletedCount > 0 && $skippedCount > 0) {
            $this->dispatch('toast', message: "Deleted {$deletedCount} subsidiary(ies). Skipped {$skippedCount} because they have active projects or users.", type: 'warning');
        } else {
            $this->dispatch('toast', message: "Cannot delete selected subsidiaries because they have active projects or users assigned.", type: 'error');
        }
    }

    public function updatedCreationType(): void
    {
        $this->azureSearchQuery   = '';
        $this->azureSearchResults = [];
        $this->selectedAzureSub   = null;
        $this->resetValidation();
    }

    public function updatedAzureSearchQuery(): void
    {
        if (strlen(trim($this->azureSearchQuery)) >= 2) {
            $this->searchAzureSubsidiaries();
        } else {
            $this->azureSearchResults = [];
        }
    }

    public function searchAzureSubsidiaries(): void
    {
        if (strlen(trim($this->azureSearchQuery)) < 2) {
            $this->azureSearchResults = [];
            return;
        }

        $this->azureSearchLoading = true;
        $this->azureSearchResults = \App\Services\AzureGraphService::searchSubsidiaries($this->azureSearchQuery, 8);
        $this->azureSearchLoading = false;
    }

    public function selectAzureSub(int $index): void
    {
        $sub = $this->azureSearchResults[$index] ?? null;
        if (!$sub) return;

        $this->selectedAzureSub   = $sub;
        $this->name               = $sub['name'];
        $this->code               = $sub['code'];
        $this->contact_email      = $sub['contact_email'];
        $this->description        = $sub['description'];
        $this->azure_id           = $sub['id'];
        $this->azureSearchResults = [];
        $this->azureSearchQuery   = $sub['name'];
    }

    public function clearAzureSelection(): void
    {
        $this->selectedAzureSub   = null;
        $this->azure_id           = null;
        $this->azureSearchQuery   = '';
        $this->azureSearchResults = [];
        $this->name               = '';
        $this->code               = '';
        $this->contact_email      = null;
        $this->description        = null;
    }

    public function updatedName($value)
    {
        if (!empty(trim($value))) {
            $this->code = $this->generateSubsidiaryCode($value);
        }
    }

    private function generateSubsidiaryCode(string $name): string
    {
        $cleanName = trim($name);
        if (empty($cleanName)) return '';

        // Ignore common suffix words
        $ignoreWords = ['pvt', 'ltd', 'limited', 'private', 'inc', 'corp', 'corporation', 'co', 'company', '&', 'and'];
        $words = preg_split('/\s+/', $cleanName);
        $filteredWords = array_filter($words, function($w) use ($ignoreWords) {
            $clean = strtolower(preg_replace('/[^a-zA-Z0-9]/', '', $w));
            return !in_array($clean, $ignoreWords);
        });

        if (empty($filteredWords)) {
            $filteredWords = $words;
        }

        $letters = '';
        foreach ($filteredWords as $w) {
            $cleanWord = preg_replace('/[^a-zA-Z0-9]/', '', $w);
            if (!empty($cleanWord)) {
                $letters .= strtoupper(substr($cleanWord, 0, 1));
            }
        }

        // Ensure prefix GS
        if (!str_starts_with($letters, 'GS')) {
            $code = 'GS' . $letters;
        } else {
            $code = $letters;
        }

        return strtoupper(substr($code, 0, 10));
    }

    public function isAuthorized(): bool
    {
        $user = auth()->user();
        if (!$user) return false;

        return $user->hasRole('super_admin')
            || $user->email === 'admin@nexuspm.local'
            || $user->id === 1;
    }

    public function openCreateModal()
    {
        if (!$this->isAuthorized()) {
            $this->dispatch('toast', message: 'Only PMO Admins can add subsidiaries.', type: 'error');
            return;
        }

        $this->reset([
            'editingId', 'code', 'name', 'description', 'address',
            'contact_email', 'contact_phone', 'status', 'logoFile',
            'creationType', 'azureSearchQuery', 'azureSearchResults', 'selectedAzureSub', 'azure_id',
        ]);
        $this->code = 'SUB-' . sprintf('%03d', Subsidiary::count() + 1);
        $this->status = 'active';
        $this->creationType = 'system';
        $this->showModal = true;
    }

    public function edit(Subsidiary $subsidiary)
    {
        if (!$this->isAuthorized()) {
            $this->dispatch('toast', message: 'Only PMO Admins can edit subsidiaries.', type: 'error');
            return;
        }

        $this->editingId      = $subsidiary->id;
        $this->code           = $subsidiary->code;
        $this->name           = $subsidiary->name;
        $this->description    = $subsidiary->description;
        $this->address        = $subsidiary->address;
        $this->contact_email  = $subsidiary->contact_email;
        $this->contact_phone  = $subsidiary->contact_phone;
        $this->status         = $subsidiary->status;
        $this->azure_id       = $subsidiary->azure_id;
        $this->creationType   = 'system';
        $this->selectedAzureSub = null;
        $this->showModal      = true;
    }

    public function save()
    {
        if (!$this->isAuthorized()) {
            $this->dispatch('toast', message: 'Only PMO Admins can save subsidiaries.', type: 'error');
            return;
        }

        $this->validate();

        $logoPath = null;
        if ($this->logoFile) {
            $logoPath = $this->logoFile->store('subsidiary-logos', 'public');
        }

        $data = [
            'code'           => strtoupper($this->code),
            'azure_id'       => $this->azure_id,
            'name'           => $this->name,
            'description'    => $this->description,
            'address'        => $this->address,
            'contact_email'  => $this->contact_email,
            'contact_phone'  => $this->contact_phone,
            'status'         => $this->status,
        ];

        if ($logoPath) {
            $data['logo'] = $logoPath;
        }

        if ($this->editingId) {
            $subsidiary = Subsidiary::findOrFail($this->editingId);
            $subsidiary->update($data);
            $action = 'updated_subsidiary';
        } else {
            $subsidiary = Subsidiary::create($data);
            $action = 'created_subsidiary';
        }

        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => $action,
            'module' => 'subsidiaries',
            'record_type' => Subsidiary::class,
            'record_id' => $subsidiary->id,
            'new_values' => $data,
        ]);

        $this->showModal = false;
        $this->dispatch('toast', message: 'Subsidiary saved successfully!', type: 'success');
    }

    public function archive(int $id)
    {
        if (!$this->isAuthorized()) {
            $this->dispatch('toast', message: 'Only PMO Admins can archive subsidiaries.', type: 'error');
            return;
        }

        $subsidiary = Subsidiary::withCount('projects')->findOrFail($id);
        if ($subsidiary->projects_count > 0) {
            $this->dispatch('toast', message: 'Cannot archive subsidiary with active projects.', type: 'error');
            return;
        }

        $subsidiary->update(['status' => 'archived']);
        $subsidiary->delete();

        $this->dispatch('toast', message: 'Subsidiary archived successfully!', type: 'success');
    }

    public function render()
    {
        $totalSubsidiaries = Subsidiary::count();
        $activeSubsidiaries = Subsidiary::where('status', 'active')->count();
        $totalProjects = Project::count();
        $totalUsers = User::count();
        $avgProgress = round(Project::avg('overall_progress') ?? 68, 1);

        $query = $this->getFilteredQuery()->withCount(['projects', 'users']);
        $subsidiaries = $query->paginate($this->perPage);

        return view('livewire.subsidiary-manager', compact(
            'subsidiaries',
            'totalSubsidiaries',
            'activeSubsidiaries',
            'totalProjects',
            'totalUsers',
            'avgProgress'
        ));
    }
}
