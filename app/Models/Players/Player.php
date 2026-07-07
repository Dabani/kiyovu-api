<?php

namespace App\Models\Players;

use App\Models\Lookups\LuPlayerTeam;
use App\Models\Lookups\LuStatus;
use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Player extends Model
{
    use Auditable, SoftDeletes;

    protected $fillable = [
        'full_name', 'date_of_birth', 'nationality', 'position', 'team_id',
        'national_id_or_passport', 'ferwafa_registration_number', 'registration_date',
        'medical_clearance_certified', 'medical_clearance_date', 'itc_reference',
        'is_minor', 'guardian_name', 'guardian_phone', 'status_id', 'created_by', 'updated_by',
    ];

    protected function casts(): array
    {
        return [
            'date_of_birth' => 'date',
            'registration_date' => 'date',
            'medical_clearance_date' => 'date',
            'medical_clearance_certified' => 'boolean',
            'is_minor' => 'boolean',
        ];
    }

    public function team(): BelongsTo
    {
        return $this->belongsTo(LuPlayerTeam::class, 'team_id');
    }

    public function status(): BelongsTo
    {
        return $this->belongsTo(LuStatus::class, 'status_id');
    }

    public function contracts(): HasMany
    {
        return $this->hasMany(PlayerContract::class);
    }

    public function loanAgreements(): HasMany
    {
        return $this->hasMany(PlayerLoanAgreement::class);
    }

    public function antiDopingDeclarations(): HasMany
    {
        return $this->hasMany(AntiDopingDeclaration::class);
    }

    public function parentalConsentForms(): HasMany
    {
        return $this->hasMany(ParentalConsentForm::class);
    }
}
