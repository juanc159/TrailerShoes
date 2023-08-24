<?php

namespace Database\Seeders;

use App\Models\Gender;
use Illuminate\Database\Seeder;

class GenderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $arrayData = [
            'Masculino',
            'Femenino',
        ];

        foreach ($arrayData as $key => $value) {
            $data = new Gender();
            $data->name = $value;
            $data->save();
        }
    }
}
