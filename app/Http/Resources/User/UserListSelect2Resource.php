<?php

namespace App\Http\Resources\User;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserListSelect2Resource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $name = $this->name ?? '';
        $lastName = $this->lastName ?? '';
        $fullName = $name.' '.$lastName;

        return [
            'id' => $this->id,
            'name' => $this->name,
            'fullName' => $fullName,
        ];
    }
}
