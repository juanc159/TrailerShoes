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
            [
                'id' => 7,
                'name' => 'product.index',
                'description' => 'Visualizar Productos',
                'menu_id' => 7,
            ],
            [
                'id' => 8,
                'name' => 'loan.index',
                'description' => 'Visualizar Prestamos',
                'menu_id' => 8,
            ],
            [
                'id' => 9,
                'name' => 'thrift.index',
                'description' => 'Visualizar Ahorros',
                'menu_id' => 9,
            ],
            [
                'id' => 10,
                'name' => 'employee.index',
                'description' => 'Visualizar Empleados',
                'menu_id' => 10,
            ],
            [
                'id' => 11,
                'name' => 'style.index',
                'description' => 'Visualizar Estilos',
                'menu_id' => 11,
            ],
            [
                'id' => 12,
                'name' => 'production.index',
                'description' => 'Visualizar Produccción',
                'menu_id' => 12,
            ],
        ];
        foreach ($arrayData as $key => $value) {
            $data = new Permission();
            $data->id = $value['id'];
            $data->name = $value['name'];
            $data->description = $value['description'];
            $data->menu_id = $value['menu_id'];
            $data->save();
        }
    }
}
