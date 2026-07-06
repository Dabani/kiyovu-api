<?php

namespace App\Http\Resources\Membership;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Generic resource for the smaller request-form models (MEM-003..007,
 * HON-001/002) — their field sets are simple enough that a bespoke
 * transformer per one adds no value. Dates are normalized, relations are
 * flattened to {id, label_en} the same way MemberResource does, and every
 * other attribute passes through as-is.
 */
class SimpleModelResource extends JsonResource
{
    /** Attributes that should render as {id, label_en, label_fr, label_rw} instead of raw arrays. */
    private const LOOKUP_RELATIONS = ['status', 'nomineeType'];

    /** Attributes that should render as {id, full_name} instead of the full nested model. */
    private const SUMMARY_RELATIONS = ['member' => 'full_name', 'nomination' => 'nominee_name'];

    public function toArray(Request $request): array
    {
        $data = $this->resource->toArray();

        // Strip internal audit FKs from the payload; keep the rest as-is.
        unset($data['created_by'], $data['updated_by'], $data['deleted_at']);

        foreach (self::LOOKUP_RELATIONS as $relation) {
            if ($this->relationLoaded($relation) && $this->{$relation}) {
                $data[$relation] = [
                    'id' => $this->{$relation}->id,
                    'label_en' => $this->{$relation}->label_en,
                    'label_fr' => $this->{$relation}->label_fr,
                    'label_rw' => $this->{$relation}->label_rw,
                ];
            }
        }

        foreach (self::SUMMARY_RELATIONS as $relation => $labelField) {
            if ($this->relationLoaded($relation) && $this->{$relation}) {
                $data[$relation] = [
                    'id' => $this->{$relation}->id,
                    'label' => $this->{$relation}->{$labelField},
                ];
            }
        }

        return $data;
    }
}
