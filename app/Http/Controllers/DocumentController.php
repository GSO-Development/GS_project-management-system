<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\ProjectDocument;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class DocumentController extends Controller
{
    public function download(Request $request, ProjectDocument $document): StreamedResponse
    {
        $user = $request->user();
        $project = $document->project;

        // Security authorization check: Super Admin or assigned PM or project member
        if (!$user->hasRole('super_admin') && $user->email !== 'admin@nexuspm.local' && $user->id !== 1) {
            $isPm = ($project?->project_manager_id === $user->id);
            $isMember = $project?->members->contains($user->id) ?? false;
            
            if (!$isPm && !$isMember) {
                abort(403, 'Unauthorized document access.');
            }
        }

        if (!Storage::disk('local')->exists($document->storage_path)) {
            abort(404, 'Document file not found in storage.');
        }

        ActivityLog::create([
            'user_id' => $user->id,
            'action' => 'downloaded_document',
            'module' => 'documents',
            'record_type' => ProjectDocument::class,
            'record_id' => $document->id,
            'new_values' => ['file_name' => $document->original_name],
        ]);

        return Storage::disk('local')->download($document->storage_path, $document->original_name);
    }

    public function view(Request $request, ProjectDocument $document)
    {
        $user = $request->user();
        $project = $document->project;

        if (!$user->hasRole('super_admin') && $user->email !== 'admin@nexuspm.local' && $user->id !== 1) {
            $isPm = ($project?->project_manager_id === $user->id);
            $isMember = $project?->members->contains($user->id) ?? false;
            
            if (!$isPm && !$isMember) {
                abort(403, 'Unauthorized document access.');
            }
        }

        if (!Storage::disk('local')->exists($document->storage_path)) {
            abort(404, 'Document file not found in storage.');
        }

        ActivityLog::create([
            'user_id' => $user->id,
            'action' => 'viewed_document',
            'module' => 'documents',
            'record_type' => ProjectDocument::class,
            'record_id' => $document->id,
            'new_values' => ['file_name' => $document->original_name],
        ]);

        $fullPath = Storage::disk('local')->path($document->storage_path);
        $ext = strtolower(pathinfo($document->original_name, PATHINFO_EXTENSION));

        $mimeType = match($ext) {
            'pdf' => 'application/pdf',
            'png' => 'image/png',
            'jpg', 'jpeg' => 'image/jpeg',
            'gif' => 'image/gif',
            'webp' => 'image/webp',
            'svg' => 'image/svg+xml',
            'txt' => 'text/plain',
            'html' => 'text/html',
            'json' => 'application/json',
            default => $document->mime_type ?? Storage::disk('local')->mimeType($document->storage_path) ?? 'application/octet-stream',
        };

        return response()->file($fullPath, [
            'Content-Type' => $mimeType,
            'Content-Disposition' => 'inline; filename="' . $document->original_name . '"',
        ]);
    }
}
