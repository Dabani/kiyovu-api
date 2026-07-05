<?php

namespace App\Models;

use App\Models\Lookups\LuDocumentClassification;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Document extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'documentable_type', 'documentable_id', 'classification_id',
        'title', 'file_path', 'original_filename', 'mime_type',
        'size_bytes', 'uploaded_by',
    ];

    public function documentable(): MorphTo
    {
        return $this->morphTo();
    }

    public function classification(): BelongsTo
    {
        return $this->belongsTo(LuDocumentClassification::class, 'classification_id');
    }

    public function uploadedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }
}
