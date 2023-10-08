<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;

    public function charge()
    {
        return $this->hasOne(Charge::class, 'id', 'charge_id');
    }
}
