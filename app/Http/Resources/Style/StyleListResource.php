<?php

namespace App\Http\Resources\Style;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StyleListResource extends JsonResource
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
            'name' => $this->name,
            'charge_name' => $this->charge->name,
            'price' => $this->price,
        ];
    }
}
