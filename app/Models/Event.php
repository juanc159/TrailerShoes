<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Event extends Model
{
    use HasFactory,SoftDeletes;

    public function users()
    {
        return $this->belongsToMany(User::class, 'event_users', 'event_id', 'user_id')->withPivot(['accept_invitation']);
    }
    public function charges()
    {
        return $this->belongsToMany(Charge::class, 'event_charges', 'event_id', 'charge_id');
    }

    public function calendar_type()
    {
        return $this->hasOne(CalendarType::class, 'id', 'calendar_type_id');
    }
}
