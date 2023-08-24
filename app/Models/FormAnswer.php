<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FormAnswer extends Model
{
    use HasFactory;

    public function input()
    {
        return $this->hasOne(FormInput::class, 'id', 'form_input_id');
    }
}
