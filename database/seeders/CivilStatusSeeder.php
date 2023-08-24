<?php

namespace Database\Seeders;

use App\Models\CivilStatus;
use Illuminate\Database\Seeder;

class CivilStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $arrayData = [
            'Soltero',
            'Casado',
        ];

        foreach ($arrayData as $key => $value) {
            $data = new CivilStatus();
            $data->name = $value;
            $data->save();
        }
    }
}
