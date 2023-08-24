<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FormInput extends Model
{
    use HasFactory;

    public function options()
    {
        return $this->hasMany(FormInputOption::class, 'form_input_id', 'id');
    }

    public function answer()
    {
        return $this->hasMany(FormAnswer::class, 'form_input_id', 'id');
    }
}
