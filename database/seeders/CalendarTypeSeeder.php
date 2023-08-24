<?php

namespace Database\Seeders;

use App\Models\CalendarType;
use Illuminate\Database\Seeder;

class CalendarTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $arrayData = [
            [
                'name' => 'Cursos',
                'color' => 'red',
            ],
            [
                'name' => 'Capacitaciones',
                'color' => 'yellow',
            ],
            [
                'name' => 'Categorias',
                'color' => 'blue',
            ],
            [
                'name' => 'Eventos',
                'color' => 'green',
            ],
        ];

        foreach ($arrayData as $key => $value) {
            $data = new CalendarType();
            $data->name = $value['name'];
            $data->color = $value['color'];
            $data->save();
        }
    }
}
