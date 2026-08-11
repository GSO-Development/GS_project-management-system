<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProjectDocument extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'project_id',
        'wbs_item_id',
        'file_name',
        'original_name',
        'mime_type',
        'file_size',
        'storage_path',
        'uploaded_by',
        'version',
        'parent_document_id',
        'description',
    ];

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function wbsItem(): BelongsTo
    {
        return $this->belongsTo(WbsItem::class);
    }

    public function uploader(): BelongsTo
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    public function parentDocument(): BelongsTo
    {
        return $this->belongsTo(ProjectDocument::class, 'parent_document_id');
    }

    public function versions(): HasMany
    {
        return $this->hasMany(ProjectDocument::class, 'parent_document_id');
    }
}
