<?php

namespace Database\Seeders;

use App\Models\Charge;
use Illuminate\Database\Seeder;

class ChargeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $arrayData = [
            [
                'name' => 'Jefe de area',
                'area_id' => 1,
                'state' => 1,
            ],
            [
                'name' => 'Guarnición',
                'area_id' => 4,
                'state' => 1,
            ],
            [
                'name' => 'Pintura',
                'area_id' => 2,
                'state' => 1,
            ],
        ];
        foreach ($arrayData as $key => $value) {
            $data = new Charge();
            $data->name = $value['name'];
            $data->area_id = $value['area_id'];
            $data->state = $value['state'];
            $data->save();
        }
    }
}
