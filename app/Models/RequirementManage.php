<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RequirementManage extends Model
{
    use HasFactory,SoftDeletes;

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    public function files()
    {
        return $this->hasMany(RequirementManageFile::class, 'requirement_manage_id', 'id');
    }
}
