<?php

namespace Database\Seeders;

use App\Models\Company;
use Illuminate\Database\Seeder;

class CompanySeeder extends Seeder{

  /**
   * Run the database seeds.
   */
  public function run(): void{
    $arrayData = [
      [
        'id' => 1,
        'name' => 'Empresa',
        // 'to' => 'Companies-Index',
        // 'icon' => 'mdi-domain',
        // 'requiredPermission' => 'companies.index',
      ],
    ];
    foreach ($arrayData as $key => $value) {
      $data = new Company();
      $data->id = $value['id'];
      $data->name = $value['name'];
      $data->save();
    }
  }
}
