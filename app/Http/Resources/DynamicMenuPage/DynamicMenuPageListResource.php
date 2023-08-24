<?php

namespace App\Http\Resources\DynamicMenuPage;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DynamicMenuPageListResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'principal' => $this->principal == 1 ? 'Si' : 'No',
            'icon' => $this->icon,
            'father_id' => $this->father_id,
            'father_name' => $this->father?->title,
            'state' => $this->state,
            'metaData' => $this->metaData,
            'children' => DynamicMenuPageListResource::collection($this->children),
        ];
    }
}
