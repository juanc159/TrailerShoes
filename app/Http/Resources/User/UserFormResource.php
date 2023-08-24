<?php

namespace App\Http\Resources\User;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserFormResource extends JsonResource
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
            'lastName' => $this->lastName,
            'email' => $this->email,
            'role_id' => $this->role_id,
            'idNumber' => $this->idNumber,
            'identity_type_id' => $this->identity_type_id,
            'charge_id' => $this->charge_id,

            "expeditionDate" => $this->expeditionDate,
            "birthDate" => $this->birthDate,
            "gender_id" => $this->gender_id,
            "weight" => $this->weight,
            "height" => $this->height,
            "civil_status_id" => $this->civil_status_id,
            "phone" => $this->phone,
            "cellphone" => $this->cellphone,
            "address" => $this->address,
            "have_son" => $this->have_son,
            "nameContact" => $this->nameContact,
            "relationshipContact" => $this->relationshipContact,
            "phoneContact" => $this->phoneContact,
            "cellphoneContact" => $this->cellphoneContact,
            "emailContact" => $this->emailContact,
        ];
    }
}
