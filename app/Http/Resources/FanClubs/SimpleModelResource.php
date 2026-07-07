<?php

namespace App\Http\Resources\FanClubs;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SimpleModelResource extends JsonResource
{
    private const LOOKUP_RELATIONS = ['status', 'incidentType', 'sanction'];

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

        if ($this->relationLoaded('fanClub') && $this->fanClub) {
            $data['fanClub'] = ['id' => $this->fanClub->id, 'label' => $this->fanClub->proposed_name];
        }

        return $data;
    }
}
