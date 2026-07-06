<?php

namespace App\Models\Membership;

use App\Models\Document;
use App\Models\Lookups\LuFeeTier;
use App\Models\Lookups\LuMembershipCategory;
use App\Models\Lookups\LuPaymentMethod;
use App\Models\Lookups\LuStatus;
use App\Models\User;
use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Member extends Model
{
  use Auditable, SoftDeletes;

  protected $fillable = [
    'user_id',
    'full_name',
    'national_id',
    'phone',
    'email',
    'statement_of_commitment',
    'category_id',
    'fee_tier_id',
    'payment_method_id',
    'application_date',
    'hardship_payment_plan',
    'acknowledged_at',
    'entry_date',
    'status_id',
    'status_since',
    'status_reason',
    'has_active_fee_waiver',
    'created_by',
    'updated_by',
  ];

  protected function casts(): array
  {
    return [
      'application_date' => 'date',
      'entry_date' => 'date',
      'status_since' => 'date',
      'acknowledged_at' => 'datetime',
      'hardship_payment_plan' => 'boolean',
      'has_active_fee_waiver' => 'boolean',
    ];
  }

  public function user(): BelongsTo
  {
    return $this->belongsTo(User::class);
  }

  public function category(): BelongsTo
  {
    return $this->belongsTo(LuMembershipCategory::class, 'category_id');
  }

  public function feeTier(): BelongsTo
  {
    return $this->belongsTo(LuFeeTier::class, 'fee_tier_id');
  }

  public function paymentMethod(): BelongsTo
  {
    return $this->belongsTo(LuPaymentMethod::class, 'payment_method_id');
  }

  public function status(): BelongsTo
  {
    return $this->belongsTo(LuStatus::class, 'status_id');
  }

  public function documents(): MorphMany
  {
    return $this->morphMany(Document::class, 'documentable');
  }

  public function informationRequests(): HasMany
  {
    return $this->hasMany(MemberInformationRequest::class);
  }

  public function inactiveStatusRequests(): HasMany
  {
    return $this->hasMany(MemberInactiveStatusRequest::class);
  }

  public function feeWaiverRequests(): HasMany
  {
    return $this->hasMany(MemberFeeWaiverRequest::class);
  }

  public function resignations(): HasMany
  {
    return $this->hasMany(MemberResignation::class);
  }

  public function reinstatementRequests(): HasMany
  {
    return $this->hasMany(MemberReinstatementRequest::class);
  }
}
