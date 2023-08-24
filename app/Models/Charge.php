<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Charge extends Model
{
    use HasFactory,SoftDeletes;

    public function users()
    {
        return $this->hasMany(User::class, 'charge_id', 'id');
    }

    public function requirementTypes()
    {
        return $this->belongsToMany(RequirementType::class, 'requirement_type_charges', 'requirement_type_id', 'charge_id');
    }
}
