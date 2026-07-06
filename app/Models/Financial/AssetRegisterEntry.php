<?php

namespace App\Models\Financial;

use App\Models\Lookups\LuAssetCategory;
use App\Models\Lookups\LuStatus;
use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class AssetRegisterEntry extends Model
{
    use Auditable, SoftDeletes;

    protected $table = 'asset_register';

    protected $fillable = [
        'asset_tag', 'description', 'category_id', 'acquisition_date', 'acquisition_cost_rwf',
        'custodian_name', 'location', 'last_verified_on', 'disposal_approved', 'disposed_on',
        'disposal_proceeds_rwf', 'status_id', 'created_by', 'updated_by',
    ];

    protected function casts(): array
    {
        return [
            'acquisition_date' => 'date',
            'last_verified_on' => 'date',
            'disposed_on' => 'date',
            'acquisition_cost_rwf' => 'integer',
            'disposal_proceeds_rwf' => 'integer',
            'disposal_approved' => 'boolean',
        ];
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(LuAssetCategory::class, 'category_id');
    }

    public function status(): BelongsTo
    {
        return $this->belongsTo(LuStatus::class, 'status_id');
    }

    public function handovers(): HasMany
    {
        return $this->hasMany(AssetHandover::class, 'asset_id');
    }
}
