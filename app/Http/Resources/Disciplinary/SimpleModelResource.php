<?php

namespace App\Http\Resources\Disciplinary;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SimpleModelResource extends JsonResource
{
    private const LOOKUP_RELATIONS = [
        'status', 'caseSource', 'sanction', 'noticeType', 'category',
        'forum', 'urgency', 'classification',
    ];

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

        // Case / intake relations get a richer identifying shape.
        foreach (['case' => 'respondent_name', 'relatedCase' => 'respondent_name', 'intake' => 'matter_description'] as $relation => $labelField) {
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
