<?php

namespace Database\Seeders;

use App\Models\Menu;
use Illuminate\Database\Seeder;

class MenuSeederUpdate20230811 extends Seeder{

  /**
   * Run the database seeds.
   */
  public function run(): void{
    $arrayData = [
      [
        'id' => 16,
        'title' => 'Empresas',
        'to' => 'Companies-Index',
        'icon' => 'mdi-domain',
        'requiredPermission' => 'companies.index',
      ],
    ];
    foreach ($arrayData as $key => $value) {
      $data = new Menu();
      $data->id = $value['id'];
      $data->title = $value['title'];
      $data->to = $value['to'];
      $data->icon = $value['icon'];
      $data->father = $value['father'] ?? null;
      $data->requiredPermission = $value['requiredPermission'];
      $data->save();
    }
  }
}
