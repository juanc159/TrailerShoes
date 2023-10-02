<?php

namespace App\Http\Controllers;

use App\Http\Requests\Employee\EmployeeStoreRequest;
use App\Http\Resources\Employee\EmployeeListResource;
use App\Http\Resources\Employee\EmployeeListSelect2Resource;
use App\Repositories\EmployeeRepository;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EmployeeController extends Controller{

  private $employedRepository;

  public function __construct(EmployeeRepository $employedRepository){
    $this->employedRepository = $employedRepository;
  }

  public function list(Request $request){
    $data = $this->employedRepository->list($request->all());
    $employees = EmployeeListResource::collection($data);

    return [
      'employees' => $employees,
      'lastPage' => $data->lastPage(),
      'totalData' => $data->total(),
      'totalPage' => $data->perPage(),
      'currentPage' => $data->currentPage(),
    ];
  }

  public function store(EmployeeStoreRequest $request){
    try {
      DB::beginTransaction();
      $data = $this->employedRepository->store($request->all());

      $msg = 'agregado';
      $action = 'create';
      if( !empty($request['id']) ){
        $msg = 'modificado';
        $action = 'update';
      }

      DB::commit();

      return response()->json(['code' => 200, 'message' => 'Registro '.$msg.' correctamente', 'data' => $data]);
    } catch (Exception $th) {
      DB::rollBack();
      return response()->json(['code' => 500, 'message' => $th->getMessage(), 'line' => $th->getLine()], 500);
    }
  }

  public function delete($id){
    try{
      DB::beginTransaction();
      $data = $this->employedRepository->find($id);
      if( $data ){
        $this->employedRepository->delete($id);
        $msg = 'Registro eliminado correctamente';

      }else{
        $msg = 'El registro no existe';
      }

      DB::commit();
      return response()->json(['code' => 200, 'message' => $msg]);
    }catch( Exception $th ){
      DB::rollBack();
      return response()->json(['code' => 500, 'message' => $th->getMessage()], 500);
    }
  }

  public function info($id){
    $aReturn = ['code' => 200];
    try{
      $data = $this->employedRepository->find($id, [], ['id', 'name']);
      $aReturn['data'] = $data;
      if( $data ){
        $aReturn['message'] = 'El registro si existe';
      }else{
        $aReturn['message'] = 'El registro no existe';
      }
    }catch(\Exception $th ){
      $aReturn['code'] = 500;
      $aReturn['message'] = $th->getMessage();
    }
    return response()->json($aReturn, $aReturn['code']);
  }

  public function dataForm(Request $request){
    $request['typeData'] = 'todos';
    return response()->json([]);
  }

  public function changeState(Request $request){
    $aReturn = ['code' => 200];
    try{
      DB::beginTransaction();
      $nId = $request->input('id');
      $state = $request->input('state');
      $model = $this->employedRepository->changeState($nId, $state, 'state');

      ($model->state == 1) ? $aReturn['msg'] = 'Activado' : $aReturn['msg'] = 'Inactivado';

      DB::commit();

      $aReturn['msg'] .= $aReturn['msg'].' con éxito';
    } catch (Exception $th) {
        DB::rollback();
        $aReturn['code'] = 500;
        $aReturn['msg'] = $th->getMessage();
    }
    return response()->json($aReturn);
  }

  public function select2InfiniteList(Request $request){
    $data = $this->employedRepository->list(request: $request->all());
    $employees = EmployeeListSelect2Resource::collection($data);

    return [
      'employees_arrayInfo' => $employees,
      'employees_countLinks' => $data->lastPage(),
    ];
  }
}
