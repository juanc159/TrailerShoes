<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DynamicMenuPage extends Model
{
    use HasFactory,SoftDeletes;

    public function children()
    {
        return $this->hasMany(DynamicMenuPage::class, 'father_id', 'id');
    }

    public function father()
    {
        return $this->hasOne(DynamicMenuPage::class, 'id', 'father_id');
    }
}
