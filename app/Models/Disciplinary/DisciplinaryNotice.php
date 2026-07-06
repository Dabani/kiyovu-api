<?php

namespace App\Models\Disciplinary;

use App\Models\Lookups\LuNoticeType;
use App\Models\Lookups\LuStatus;
use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class DisciplinaryNotice extends Model
{
    use Auditable, SoftDeletes;

    protected $fillable = [
        'case_id', 'notice_type_id', 'issued_on', 'response_deadline', 'hearing_date',
        'hearing_venue', 'allegations_summary', 'respondent_acknowledged',
        'respondent_response_received_on', 'status_id', 'created_by', 'updated_by',
    ];

    protected function casts(): array
    {
        return [
            'issued_on' => 'date',
            'response_deadline' => 'date',
            'hearing_date' => 'date',
            'respondent_response_received_on' => 'date',
            'respondent_acknowledged' => 'boolean',
        ];
    }

    public function case(): BelongsTo
    {
        return $this->belongsTo(DisciplinaryCase::class, 'case_id');
    }

    public function noticeType(): BelongsTo
    {
        return $this->belongsTo(LuNoticeType::class, 'notice_type_id');
    }

    public function status(): BelongsTo
    {
        return $this->belongsTo(LuStatus::class, 'status_id');
    }
}
