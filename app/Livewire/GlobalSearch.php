<?php

namespace App\Livewire;

use App\Models\Project;
use App\Models\ProjectDocument;
use App\Models\Subsidiary;
use App\Models\User;
use App\Models\WbsItem;
use Livewire\Component;

class GlobalSearch extends Component
{
    public string $query = '';

    public function render()
    {
        $user = auth()->user();
        $results = ['projects' => [], 'wbs' => [], 'users' => [], 'subsidiaries' => [], 'documents' => []];

        if (strlen($this->query) >= 2) {
            // Projects
            $pQuery = Project::where('name', 'like', "%{$this->query}%")->orWhere('code', 'like', "%{$this->query}%");
            if ($user->hasRole('project_manager')) {
                $pQuery->where('project_manager_id', $user->id);
            } elseif ($user->hasAnyRole(['team_member', 'collaborator'])) {
                $pQuery->whereHas('members', fn($q) => $q->where('user_id', $user->id));
            }
            $results['projects'] = $pQuery->take(5)->get();

            // WBS Items
            $wQuery = WbsItem::where('title', 'like', "%{$this->query}%")->orWhere('wbs_code', 'like', "%{$this->query}%");
            if ($user->hasAnyRole(['team_member', 'collaborator'])) {
                $wQuery->where('assigned_user_id', $user->id);
            }
            $results['wbs'] = $wQuery->take(5)->get();

            // Subsidiaries & Users for Super Admin
            if ($user->hasRole('super_admin')) {
                $results['subsidiaries'] = Subsidiary::where('name', 'like', "%{$this->query}%")->take(5)->get();
                $results['users'] = User::where('name', 'like', "%{$this->query}%")->orWhere('email', 'like', "%{$this->query}%")->take(5)->get();
            }

            // Documents
            $dQuery = ProjectDocument::where('original_name', 'like', "%{$this->query}%");
            $results['documents'] = $dQuery->take(5)->get();
        }

        return view('livewire.global-search', compact('results'));
    }
}
