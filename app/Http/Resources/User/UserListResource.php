<?php

namespace App\Http\Resources\User;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserListResource extends JsonResource
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
            'role_name' => $this->role?->name,
            'state' => $this->state,
            'idNumber' => $this->idNumber,
            'identityType_name' => $this->identityType?->name,
            'charge_name' => $this->charge?->name,
            'email' => $this->email,
            'state' => $this->state,
        ];
    }
}
