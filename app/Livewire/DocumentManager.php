<?php

namespace App\Livewire;

use App\Models\Project;
use App\Models\ProjectDocument;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithPagination;

class DocumentManager extends Component
{
    use WithPagination;

    public string $search = '';
    public string $projectFilter = 'all';

    /* ── Document Preview Modal State ── */
    public bool $showPreviewModal = false;
    public ?int $previewDocId = null;
    public ?string $previewContent = null;
    public ?string $previewFileType = null;

    public function openPreview(int $documentId)
    {
        $doc = ProjectDocument::with(['project', 'uploader'])->findOrFail($documentId);
        $this->previewDocId = $doc->id;

        $ext = strtolower(pathinfo($doc->original_name, PATHINFO_EXTENSION));
        $this->previewFileType = $ext;

        if (Storage::disk('local')->exists($doc->storage_path)) {
            $filePath = Storage::disk('local')->path($doc->storage_path);

            if (in_array($ext, ['docx', 'doc'])) {
                $this->previewContent = $this->extractDocxText($filePath);
            } elseif (in_array($ext, ['txt', 'csv', 'json', 'log', 'md', 'xml', 'html'])) {
                $this->previewContent = Storage::disk('local')->get($doc->storage_path);
            } else {
                $this->previewContent = null;
            }
        } else {
            $this->previewContent = null;
        }

        $this->showPreviewModal = true;
    }

    public function closePreview()
    {
        $this->showPreviewModal = false;
        $this->previewDocId = null;
        $this->previewContent = null;
        $this->previewFileType = null;
    }

    private function extractDocxText(string $filePath): string
    {
        if (!class_exists('ZipArchive')) return '';
        $zip = new \ZipArchive();
        if ($zip->open($filePath) === true) {
            if (($index = $zip->locateName('word/document.xml')) !== false) {
                $data = $zip->getFromIndex($index);
                $zip->close();
                $xml = str_replace(['</w:p>', '</w:tr>', '<w:br/>'], ["\n\n", "\n", "\n"], $data);
                return trim(html_entity_decode(strip_tags($xml)));
            }
            $zip->close();
        }
        return '';
    }

    public function deleteDocument(int $documentId)
    {
        $doc = ProjectDocument::with('project')->findOrFail($documentId);
        $user = auth()->user();

        if ($doc->uploaded_by !== $user->id && !$user->hasRole('super_admin') && $doc->project?->project_manager_id !== $user->id) {
            $this->dispatch('toast', message: 'Unauthorized to delete this document.', type: 'error');
            return;
        }

        if (Storage::disk('local')->exists($doc->storage_path)) {
            Storage::disk('local')->delete($doc->storage_path);
        }

        $doc->delete();

        $this->dispatch('toast', message: 'Document deleted successfully!', type: 'info');
    }

    public function render()
    {
        $user = auth()->user();

        $query = ProjectDocument::with(['project', 'uploader']);

        if (!$user->hasRole('super_admin') && $user->email !== 'admin@nexuspm.local' && $user->id !== 1) {
            $query->whereHas('project', function($q) use ($user) {
                $q->where('project_manager_id', $user->id)
                  ->orWhere(function($subQ) use ($user) {
                      $subQ->where('pm_accepted', true)
                           ->whereHas('members', fn($mq) => $mq->where('user_id', $user->id));
                  });
            });
        }

        if ($this->search) {
            $query->where('original_name', 'like', "%{$this->search}%");
        }

        if ($this->projectFilter !== 'all') {
            $query->where('project_id', $this->projectFilter);
        }

        $documents = $query->latest()->paginate(10);
        
        if (!$user->hasRole('super_admin') && $user->email !== 'admin@nexuspm.local' && $user->id !== 1) {
            $projects = Project::where(function($q) use ($user) {
                    $q->where('project_manager_id', $user->id)
                      ->orWhere(function($subQ) use ($user) {
                          $subQ->where('pm_accepted', true)
                               ->whereHas('members', fn($mq) => $mq->where('user_id', $user->id));
                      });
                })
                ->get();
        } else {
            $projects = Project::all();
        }

        $previewDoc = $this->previewDocId ? ProjectDocument::with(['project', 'uploader'])->find($this->previewDocId) : null;

        return view('livewire.document-manager', compact('documents', 'projects', 'previewDoc'));
    }
}
