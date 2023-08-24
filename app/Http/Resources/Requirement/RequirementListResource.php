<?php

namespace App\Http\Resources\Requirement;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RequirementListResource extends JsonResource
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
            'user_id' => $this->user_id,
            'state_id' => $this->requirement_state_id,
            'state' => $this->state?->name,
            'type' => $this->type?->name,
            'archive_final' => $this->archive_final,
        ];
    }
}
