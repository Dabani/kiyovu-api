<?php

namespace App\Http\Resources\Membership;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MemberResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'full_name' => $this->full_name,
            'national_id' => $this->national_id,
            'phone' => $this->phone,
            'email' => $this->email,
            'category' => $this->whenLoaded('category', fn () => [
                'id' => $this->category->id,
                'label_en' => $this->category->label_en,
                'label_fr' => $this->category->label_fr,
                'label_rw' => $this->category->label_rw,
            ]),
            'fee_tier' => $this->whenLoaded('feeTier', fn () => [
                'id' => $this->feeTier->id,
                'label_en' => $this->feeTier->label_en,
                'min_monthly_rwf' => $this->feeTier->min_monthly_rwf,
                'max_monthly_rwf' => $this->feeTier->max_monthly_rwf,
            ]),
            'status' => $this->whenLoaded('status', fn () => [
                'id' => $this->status->id,
                'label_en' => $this->status->label_en,
                'color_hex' => $this->status->color_hex,
            ]),
            'application_date' => $this->application_date?->toDateString(),
            'entry_date' => $this->entry_date?->toDateString(),
            'acknowledged_at' => $this->acknowledged_at?->toIso8601String(),
            'hardship_payment_plan' => $this->hardship_payment_plan,
            'has_active_fee_waiver' => $this->has_active_fee_waiver,
            'created_at' => $this->created_at?->toIso8601String(),
            'updated_at' => $this->updated_at?->toIso8601String(),
        ];
    }
}
