<?php

namespace Database\Seeders;

use App\Models\RequirementState;
use Illuminate\Database\Seeder;

class RequirementStateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $arrayData = [[
            'id' => 1,
            'name' => 'Creado',
        ],
            [
                'id' => 2,
                'name' => 'En proceso',
            ],
            [
                'id' => 3,
                'name' => 'Finalizado',
            ],
            [
                'id' => 4,
                'name' => 'generado',
            ],

        ];
        foreach ($arrayData as $key => $value) {
            $data = new RequirementState();
            $data->id = $value['id'];
            $data->name = $value['name'];
            $data->save();
        }
    }
}
