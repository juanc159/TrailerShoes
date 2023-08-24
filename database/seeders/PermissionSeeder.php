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
                'name' => 'requirementType.index',
                'description' => 'Visualizar tipo de solicitud',
                'menu_id' => 6,
            ],
            [
                'id' => 7,
                'name' => 'requirement.index',
                'description' => 'Visualizar Requerimientos',
                'menu_id' => 7,
            ],
            [
                'id' => 8,
                'name' => 'socialNetwork.index',
                'description' => 'Visualizar Redes Sociales',
                'menu_id' => 8,
            ],
            [
                'id' => 9,
                'name' => 'survey.index',
                'description' => 'Visualizar Encuestas',
                'menu_id' => 9,
            ],
            [
                'id' => 10,
                'name' => 'dynamicMenuPage.index',
                'description' => 'Visualizar Menu Dinamico Paginas',
                'menu_id' => 10,
            ],
            [
                'id' => 11,
                'name' => 'calendar.index',
                'description' => 'Visualizar Calendario',
                'menu_id' => 11,
            ],
            [
                'id' => 12,
                'name' => 'calendarType.index',
                'description' => 'Visualizar Tipo Calendario',
                'menu_id' => 12,
            ],
            [
                'id' => 13,
                'name' => 'form.index',
                'description' => 'Visualizar Formularios',
                'menu_id' => 13,
            ],
            [
                'id' => 14,
                'name' => 'petition.index',
                'description' => 'Visualizar Mis solicitudes',
                'menu_id' => 14,
            ],
            [
                'id' => 15,
                'name' => 'audit.index',
                'description' => 'Visualizar Modulo Auditoria',
                'menu_id' => 15,
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
