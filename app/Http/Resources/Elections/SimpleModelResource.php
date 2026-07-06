<?php

namespace App\Http\Resources\Elections;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SimpleModelResource extends JsonResource
{
    private const LOOKUP_RELATIONS = ['status', 'position', 'disputeGround'];

    private const SUMMARY_RELATIONS = ['member' => 'full_name'];

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

        // Nomination relations get their own richer shape (candidate name matters more than a generic label).
        foreach (['nomination', 'winningNomination'] as $relation) {
            if ($this->relationLoaded($relation) && $this->{$relation}) {
                $data[$relation] = [
                    'id' => $this->{$relation}->id,
                    'candidate_full_name' => $this->{$relation}->candidate_full_name,
                ];
            }
        }

        return $data;
    }
}
