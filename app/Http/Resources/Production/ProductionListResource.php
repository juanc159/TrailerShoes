<?php

namespace App\Http\Resources\Production;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductionListResource extends JsonResource
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
            'style_name' => $this->style->name,
            'employee_name' => $this->employee->name,
            'cant' => $this->cant,
            'total' => $this->total,
        ];
    }
}
