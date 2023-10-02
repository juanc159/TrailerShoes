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
                'icon' => 'mdi-account',
                'requiredPermission' => 'user.index',
            ],
            [
                'id' => 2,
                'title' => 'Roles',
                'to' => 'Role-Index',
                'icon' => 'mdi-lock-off-outline',
                'requiredPermission' => 'role.index',
            ], 
            [
                'id' => 3,
                'title' => 'Menu',
                'to' => 'Menu-Index',
                'icon' => 'mdi-format-list-checkbox',
                'requiredPermission' => 'menu.index',
            ],
            [
                'id' => 4,
                'title' => 'Permisos',
                'to' => 'Permission-Index',
                'icon' => 'mdi-security',
                'requiredPermission' => 'permission.index',
            ],
            [
                'id' => 5,
                'title' => 'Cargos',
                'to' => 'Charge-Index',
                'icon' => 'mdi-account-tie',
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
                'icon' => 'mdi-shoe-sneaker',
                'requiredPermission' => 'product.index',
            ],
            [
                'id' => 8,
                'title' => 'Prestamos',
                'to' => 'Loan-Index',
                'icon' => 'mdi-currency-usd-off',
                'requiredPermission' => 'loan.index',
            ],
            [
                'id' => 9,
                'title' => 'Configuración',
                'to' => 'Loan-Index',
                'icon' => 'mdi-cog',
                'requiredPermission' => 'config.index',
            ],
            [
                'id' => 10,
                'title' => 'Ahorros',
                'to' => 'Thrift-Index',
                'icon' => 'mdi-cash-lock',
                'requiredPermission' => 'thrift.index',
                'father' => 9
            ],
            [
                'id' => 11,
                'title' => 'Empleados',
                'to' => 'Employee-Index',
                'icon' => 'mdi-briefcase-outline',
                'requiredPermission' => 'employee.index',
                'father' => 9
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
