<?php

namespace App\Models\Disciplinary;

use App\Models\Lookups\LuLegalForum;
use App\Models\Lookups\LuLegalUrgency;
use App\Models\Lookups\LuStatus;
use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class LegalMatterIntake extends Model
{
    use Auditable, SoftDeletes;

    protected $fillable = [
        'matter_description', 'notified_by_name', 'notified_by_role', 'notified_on',
        'forum_id', 'urgency_id', 'classified_on', 'deadline_date', 'reported_to_president',
        'status_id', 'created_by', 'updated_by',
    ];

    protected function casts(): array
    {
        return [
            'notified_on' => 'date',
            'classified_on' => 'date',
            'deadline_date' => 'date',
            'reported_to_president' => 'boolean',
        ];
    }

    public function forum(): BelongsTo
    {
        return $this->belongsTo(LuLegalForum::class, 'forum_id');
    }

    public function urgency(): BelongsTo
    {
        return $this->belongsTo(LuLegalUrgency::class, 'urgency_id');
    }

    public function status(): BelongsTo
    {
        return $this->belongsTo(LuStatus::class, 'status_id');
    }

    public function registerEntry(): HasOne
    {
        return $this->hasOne(LegalCaseRegister::class, 'intake_id');
    }
}
