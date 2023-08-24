<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RequirementType extends Model
{
    use HasFactory,SoftDeletes;

    public function charges()
    {
        return $this->belongsToMany(Charge::class, 'requirement_type_charges', 'requirement_type_id', 'charge_id')
            ->withPivot('order')->orderByPivot('order');
    }

    public function form()
    {
        return $this->HasOne(Form::class, 'requirement_type_id', 'id');
    }
}
