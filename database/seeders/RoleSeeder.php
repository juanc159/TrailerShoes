<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $arrayData = [
            [
                'id' => 1,
                'name' => 'Administrador',
                'description' => 'Administrador',
                'pageInitial' => 'Calendar-Index',
                'permissions' => [1, 2, 3, 4, 5, 6,7,8,9,10],
            ],

        ];
        foreach ($arrayData as $key => $value) {
            $data = new Role();
            $data->id = $value['id'];
            $data->name = $value['name'];
            $data->description = $value['description'];
            $data->pageInitial = $value['pageInitial'];
            $data->save();
            $data->permissions()->sync($value['permissions']);
        }
    }
}
