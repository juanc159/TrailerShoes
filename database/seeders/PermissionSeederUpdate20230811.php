<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionSeederUpdate20230811 extends Seeder{

  /**
   * Run the database seeds.
   */
  public function run(): void{
    $arrayData = [
      [
        'id' => 16,
        'name' => 'companies.index',
        'description' => 'Visualizar Empresas',
        'menu_id' => 16,
      ],
    ];
    foreach ($arrayData as $key => $value) {
      $data = new Permission();
      $data->name = $value['name'];
      $data->description = $value['description'];
      $data->menu_id = $value['menu_id'];
      $data->save();
    }
  }
}
