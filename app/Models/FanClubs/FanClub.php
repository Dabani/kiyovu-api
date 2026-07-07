<?php

namespace App\Models\FanClubs;

use App\Models\Lookups\LuStatus;
use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class FanClub extends Model
{
    use Auditable, SoftDeletes;

    protected $fillable = [
        'proposed_name', 'founding_members_count', 'objectives_statement', 'charter_provided',
        'chairperson_name', 'secretary_name', 'treasurer_name', 'code_of_conduct_commitment',
        'designated_account_reference', 'application_date', 'certificate_number', 'recognized_on',
        'signed_by_president_name', 'registration_fee_due_on', 'registration_fee_paid',
        'status_id', 'created_by', 'updated_by',
    ];

    protected function casts(): array
    {
        return [
            'application_date' => 'date',
            'recognized_on' => 'date',
            'registration_fee_due_on' => 'date',
            'charter_provided' => 'boolean',
            'code_of_conduct_commitment' => 'boolean',
            'registration_fee_paid' => 'boolean',
            'founding_members_count' => 'integer',
        ];
    }

    public function status(): BelongsTo
    {
        return $this->belongsTo(LuStatus::class, 'status_id');
    }

    public function annualReports(): HasMany
    {
        return $this->hasMany(FanClubAnnualReport::class);
    }

    public function financialSummaries(): HasMany
    {
        return $this->hasMany(FanClubFinancialSummary::class);
    }

    public function incidentReports(): HasMany
    {
        return $this->hasMany(FanIncidentReport::class);
    }

    public function paymentConfirmations(): HasMany
    {
        return $this->hasMany(FanClubPaymentConfirmation::class);
    }

    public function membershipRegisters(): HasMany
    {
        return $this->hasMany(FanClubMembershipRegister::class);
    }
}
