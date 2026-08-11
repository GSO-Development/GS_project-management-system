<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ApprovalDocument extends Model
{
    use HasFactory;

    protected $fillable = [
        'approval_request_id',
        'file_name',
        'original_name',
        'storage_path',
        'file_size',
        'mime_type',
        'uploaded_by',
    ];

    public function approvalRequest(): BelongsTo
    {
        return $this->belongsTo(ApprovalRequest::class);
    }
}
