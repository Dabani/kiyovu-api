<?php

namespace App\Http\Resources\Financial;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SimpleModelResource extends JsonResource
{
    private const LOOKUP_RELATIONS = ['status', 'expenditureTier', 'category', 'contractType'];

    private const SUMMARY_RELATIONS = ['asset' => 'description'];

    public function toArray(Request $request): array
    {
        $data = $this->resource->toArray();
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
