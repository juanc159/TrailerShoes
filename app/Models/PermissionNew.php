<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Permission\Models\Permission;

class PermissionNew extends Permission
{
    use HasFactory;

    public function menu()
    {
        return $this->hasOne(Menu::class, 'id', 'menu_id');
    }
}
