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
                'title' => 'Tipo de solicitud',
                'to' => 'RequirementType-Index',
                'icon' => 'mdi-arrow-right-thin-circle-outline',
                'requiredPermission' => 'requirementType.index',
            ],
            [
                'id' => 7,
                'title' => 'Requerimientos',
                'to' => 'Requirement-Index',
                'icon' => 'mdi-arrow-right-thin-circle-outline',
                'requiredPermission' => 'requirement.index',
            ],
            [
                'id' => 8,
                'title' => 'Redes sociales',
                'to' => 'SocialNetwork-Index',
                'icon' => 'mdi-arrow-right-thin-circle-outline',
                'requiredPermission' => 'socialNetwork.index',
            ],
            [
                'id' => 9,
                'title' => 'Encuestas',
                'to' => 'Survey-Index',
                'icon' => 'mdi-arrow-right-thin-circle-outline',
                'requiredPermission' => 'survey.index',
            ],
            [
                'id' => 10,
                'title' => 'Menu Dinamico Paginas',
                'to' => 'DynamicMenuPage-Index',
                'icon' => 'mdi-arrow-right-thin-circle-outline',
                'requiredPermission' => 'dynamicMenuPage.index',
            ],
            [
                'id' => 11,
                'title' => 'Calendario',
                'to' => 'Calendar-Index',
                'icon' => 'mdi-arrow-right-thin-circle-outline',
                'requiredPermission' => 'calendar.index',
            ],
            [
                'id' => 12,
                'title' => 'Tipo Calendario',
                'to' => 'CalendarType-Index',
                'icon' => 'mdi-arrow-right-thin-circle-outline',
                'requiredPermission' => 'calendarType.index',
            ],
            [
                'id' => 13,
                'title' => 'Formularios',
                'to' => 'Form-Index',
                'icon' => 'mdi-arrow-right-thin-circle-outline',
                'requiredPermission' => 'form.index',
            ],
            [
                'id' => 14,
                'title' => 'Mis solicitudes',
                'to' => 'Requirement-Index',
                'icon' => 'mdi-arrow-right-thin-circle-outline',
                'requiredPermission' => 'petition.index',
            ],
            [
                'id' => 15,
                'title' => 'Auditoria',
                'to' => 'Audit-Index',
                'icon' => 'mdi-arrow-right-thin-circle-outline',
                'requiredPermission' => 'audit.index',
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
