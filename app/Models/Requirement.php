<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Requirement extends Model
{
    use HasFactory,SoftDeletes;

    public function state()
    {
        return $this->hasOne(RequirementState::class, 'id', 'requirement_state_id');
    }

    public function type()
    {
        return $this->hasOne(RequirementType::class, 'id', 'requirement_type_id');
    }

    public function manages()
    {
        return $this->hasMany(RequirementManage::class, 'requirement_id', 'id');
    }

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }
}
