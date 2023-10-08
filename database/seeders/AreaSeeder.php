<?php

namespace Database\Seeders;

use App\Models\Area;
use Illuminate\Database\Seeder;

class AreaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $arrayData = [
            [
                'name' => 'Administrativo',
            ],
            [
                'name' => 'Terminación',
            ],
            [
                'name' => 'Corte',
            ],
            [
                'name' => 'Guarnición',
            ],
            [
                'name' => 'Soldadura',
            ]
        ];
        foreach ($arrayData as $key => $value) {
            $data = new Area();
            $data->name = $value['name'];
            $data->save();
        }
    }
}
