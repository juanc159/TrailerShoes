<?php

namespace Database\Seeders;

use App\Models\IdentityType;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder{

  /**
   * Run the database seeds.
  */
  public function run(): void{
    $role = Role::first();
    $identityType = IdentityType::first();
    $data = new User();
    $data->name = 'admin';
    $data->lastName = 'admin';
    $data->email = 'admin@admin.com';
    $data->idNumber = 'admin@admin.com';
    $data->identity_type_id = $identityType->id;
    $data->charge_id = null;
    $data->password = Hash::make('admin@admin.com');
    $data->role_id = $role->id;
    $data->save();
    $data->roles()->sync($data->role_id);
  }
}
