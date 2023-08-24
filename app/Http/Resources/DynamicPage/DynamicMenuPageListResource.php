<?php

namespace App\Http\Resources\DynamicPage;

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
            'to' => $this->to,
            'icon' => $this->icon,
            'father' => $this->father,
            'children' => DynamicMenuPageListResource::collection($this->children),
        ];
    }
}
