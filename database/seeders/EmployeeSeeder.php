<?php

namespace Database\Seeders;

use App\Models\Employee;
use Illuminate\Database\Seeder;

class EmployeeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $arrayData = [
            [
                'name' => 'Juan',
                'last_name' => 'Pepito',
                'number_identification' => '123435676',
                'charge_id' => 1,
                'phone' => '234234324',
                'indicator' => '1',
            ],
            [
                'name' => 'Felipe',
                'last_name' => 'Pepito',
                'number_identification' => '123435676',
                'charge_id' => 1,
                'phone' => '234234324',
                'indicator' => '1',
            ],
        ];
        foreach ($arrayData as $key => $value) {
            $data = new Employee();
            $data->name = $value['name'];
            $data->last_name = $value['last_name'];
            $data->number_identification = $value['number_identification'];
            $data->charge_id = $value['charge_id'];
            $data->phone = $value['phone'];
            $data->indicator = $value['indicator'];
            $data->save();
        }
    }
}
