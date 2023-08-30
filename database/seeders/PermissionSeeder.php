<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $arrayData = [
            [
                'id' => 1,
                'name' => 'user.index',
                'description' => 'Visualizar usuarios',
                'menu_id' => 1,
            ],
            [
                'id' => 2,
                'name' => 'role.index',
                'description' => 'Visualizar roles',
                'menu_id' => 2,
            ],
            [
                'id' => 3,
                'name' => 'permission.index',
                'description' => 'Visualizar Permisos',
                'menu_id' => 3,
            ],
            [
                'id' => 4,
                'name' => 'menu.index',
                'description' => 'Visualizar menu',
                'menu_id' => 4,
            ],
            [
                'id' => 5,
                'name' => 'charge.index',
                'description' => 'Visualizar cargos',
                'menu_id' => 5,
            ],
            [
                'id' => 6,
                'name' => 'companies.index',
                'description' => 'Visualizar Empresas',
                'menu_id' => 6,
              ],
        ];
        foreach ($arrayData as $key => $value) {
            $data = new Permission();
            $data->name = $value['name'];
            $data->description = $value['description'];
            $data->menu_id = $value['menu_id'];
            $data->save();
        }
    }
}
