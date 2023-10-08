<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Production extends Model
{
    use HasFactory;

    public function style(){
        return $this->hasOne(Style::class,"id","style_id");
    }
    public function employee(){
        return $this->hasOne(Employee::class,"id","employee_id");
    }
}
