<?php

namespace App\Models\Disciplinary;

use App\Models\Lookups\LuDocumentClassification;
use App\Models\Lookups\LuLegalForum;
use App\Models\Lookups\LuStatus;
use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class LegalCaseRegister extends Model
{
    use Auditable, SoftDeletes;

    protected $table = 'legal_case_register';

    protected $fillable = [
        'intake_id', 'case_reference', 'forum_id', 'classification_id', 'opened_on',
        'last_updated_on', 'outcome', 'closed_on', 'reported_to_executive_organ_quarterly',
        'reported_to_ga_annually', 'status_id', 'created_by', 'updated_by',
    ];

    protected function casts(): array
    {
        return [
            'opened_on' => 'date',
            'last_updated_on' => 'date',
            'closed_on' => 'date',
            'reported_to_executive_organ_quarterly' => 'boolean',
            'reported_to_ga_annually' => 'boolean',
        ];
    }

    public function intake(): BelongsTo
    {
        return $this->belongsTo(LegalMatterIntake::class, 'intake_id');
    }

    public function forum(): BelongsTo
    {
        return $this->belongsTo(LuLegalForum::class, 'forum_id');
    }

    public function classification(): BelongsTo
    {
        return $this->belongsTo(LuDocumentClassification::class, 'classification_id');
    }

    public function status(): BelongsTo
    {
        return $this->belongsTo(LuStatus::class, 'status_id');
    }
}
