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
            if (!$user->hasRole('super_admin') && $user->email !== 'admin@nexuspm.local' && $user->id !== 1) {
                $pQuery->where(function($q) use ($user) {
                    $q->where('project_manager_id', $user->id)
                      ->orWhereHas('members', fn($mq) => $mq->where('user_id', $user->id));
                });
            }
            $results['projects'] = $pQuery->take(5)->get();

            // WBS Items
            $wQuery = WbsItem::where('title', 'like', "%{$this->query}%")->orWhere('wbs_code', 'like', "%{$this->query}%");
            if (!$user->hasRole('super_admin') && $user->email !== 'admin@nexuspm.local' && $user->id !== 1) {
                $wQuery->where(function($q) use ($user) {
                    $q->whereHas('project', fn($pq) => $pq->where('project_manager_id', $user->id))
                      ->orWhere('assigned_user_id', $user->id);
                });
            }
            $results['wbs'] = $wQuery->take(5)->get();

            // Subsidiaries & Users for Super Admin
            if ($user->hasRole('super_admin')) {
                $results['subsidiaries'] = Subsidiary::where('name', 'like', "%{$this->query}%")->take(5)->get();
                $results['users'] = User::where('name', 'like', "%{$this->query}%")->orWhere('email', 'like', "%{$this->query}%")->take(5)->get();
            }

            // Documents
            $dQuery = ProjectDocument::where('original_name', 'like', "%{$this->query}%");
            if (!$user->hasRole('super_admin') && $user->email !== 'admin@nexuspm.local' && $user->id !== 1) {
                $dQuery->whereHas('project', function($pq) use ($user) {
                    $pq->where('project_manager_id', $user->id)
                      ->orWhereHas('members', fn($mq) => $mq->where('user_id', $user->id));
                });
            }
            $results['documents'] = $dQuery->take(5)->get();
        }

        return view('livewire.global-search', compact('results'));
    }
}
