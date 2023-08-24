<?php

namespace App\Http\Resources\LogInfo;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LogInfoListResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $fullname = $this->user?->name.' '.$this->user?->lastName;

        return [
            'id' => $this->id,
            'action' => $this->action,
            'module' => $this->module,
            'description' => $this->description,
            'user_name' => trim($fullname),
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
            'before' => $this->before,
            'after' => $this->after,
        ];
    }
}
