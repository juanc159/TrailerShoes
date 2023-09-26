<?php

namespace Database\Seeders;

use App\Models\Menu;
use Illuminate\Database\Seeder;

class MenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $arrayData = [
            [
                'id' => 1,
                'title' => 'Usuarios',
                'to' => 'User-Index',
                'icon' => 'mdi-arrow-right-thin-circle-outline',
                'requiredPermission' => 'user.index',
            ],

            [
                'id' => 2,
                'title' => 'Roles',
                'to' => 'Role-Index',
                'icon' => 'mdi-arrow-right-thin-circle-outline',
                'requiredPermission' => 'role.index',
            ], [
                'id' => 3,
                'title' => 'Menu',
                'to' => 'Menu-Index',
                'icon' => 'mdi-arrow-right-thin-circle-outline',
                'requiredPermission' => 'menu.index',
            ],
            [
                'id' => 4,
                'title' => 'Permisos',
                'to' => 'Permission-Index',
                'icon' => 'mdi-arrow-right-thin-circle-outline',
                'requiredPermission' => 'permission.index',
            ],
            [
                'id' => 5,
                'title' => 'Cargos',
                'to' => 'Charge-Index',
                'icon' => 'mdi-arrow-right-thin-circle-outline',
                'requiredPermission' => 'charge.index',
            ],
            [
                'id' => 6,
                'title' => 'Empresa',
                'to' => 'Companies-Index',
                'icon' => 'mdi-domain',
                'requiredPermission' => 'companies.index',
            ],
            [
                'id' => 7,
                'title' => 'Productos',
                'to' => 'Product-Index',
                'icon' => 'mdi-domain',
                'requiredPermission' => 'product.index',
            ],
            [
                'id' => 8,
                'title' => 'Prestamos',
                'to' => 'Loan-Index',
                'icon' => 'mdi-domain',
                'requiredPermission' => 'loan.index',
            ],
            [
                'id' => 9,
                'title' => 'Ahorros',
                'to' => 'Thrift-Index',
                'icon' => 'mdi-domain',
                'requiredPermission' => 'thrift.index',
            ],
        ];
        foreach ($arrayData as $key => $value) {
            $data = new Menu();
            $data->id = $value['id'];
            $data->title = $value['title'];
            $data->to = $value['to'];
            $data->icon = $value['icon'];
            $data->father = $value['father'] ?? null;
            $data->requiredPermission = $value['requiredPermission'];
            $data->save();
        }
    }
}
