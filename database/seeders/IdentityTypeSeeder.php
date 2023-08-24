<?php

namespace Database\Seeders;

use App\Models\IdentityType;
use Illuminate\Database\Seeder;

class IdentityTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $arrayData = [
            'Cedula de identidad',
            'Pasaporte',
        ];

        foreach ($arrayData as $key => $value) {
            $data = new IdentityType();
            $data->name = $value;
            $data->save();
        }
    }
}
