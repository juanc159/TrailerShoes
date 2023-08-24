<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Form extends Model
{
    use HasFactory,SoftDeletes;

    public function inputs()
    {
        return $this->hasMany(FormInput::class, 'form_id', 'id');
    }

    public function answers()
    {
        return $this->hasMany(FormAnswer::class, 'form_id', 'id');
    }

    public function requirement_type()
    {
        return $this->hasOne(RequirementType::class, 'id', 'requirement_type_id');
    }
}
