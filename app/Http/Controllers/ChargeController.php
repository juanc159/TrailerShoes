<?php

namespace App\Http\Controllers;

use App\Http\Requests\Charge\ChargeStoreRequest;
use App\Http\Resources\Charge\ChargeListResource;
use App\Http\Resources\Charge\ChargeListSelect2Resource;
use App\Repositories\ChargeRepository;
use App\Repositories\AreaRepository;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ChargeController extends Controller{

  private $chargeRepository;
  private $areaRepository;

  public function __construct(
    ChargeRepository $chargeRepository,
    AreaRepository $areaRepository
    ){
    $this->chargeRepository = $chargeRepository;
    $this->areaRepository = $areaRepository;
  }

  public function list(Request $request){
    $data = $this->chargeRepository->list($request->all(), ["area"]);
    $charges = ChargeListResource::collection($data);

    return [
      'charges' => $charges,
      'lastPage' => $data->lastPage(),
      'totalData' => $data->total(),
      'totalPage' => $data->perPage(),
      'currentPage' => $data->currentPage(),
    ];
  }

  public function store(ChargeStoreRequest $request){
    try {
      DB::beginTransaction();
       $this->chargeRepository->find($request->input("id"));
      $data = $this->chargeRepository->store($request->all());

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
     $data = $this->chargeRepository->find($id);
      if( $data ){
        $this->chargeRepository->delete($id);
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
      $data = $this->chargeRepository->find($id, []);
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
    return response()->json([
      "areas" => $this->areaRepository->selectList()
    ]);
  }

  public function changeState(Request $request){
    $aReturn = ['code' => 200];
    try{
      DB::beginTransaction();
      $nId = $request->input('id');
      $state = $request->input('state');
      $this->chargeRepository->find($request->input('id'));
       $model = $this->chargeRepository->changeState($nId, $state, 'state');

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
    $data = $this->chargeRepository->list(request: $request->all());
    $charges = ChargeListSelect2Resource::collection($data);

    return [
      'charges_arrayInfo' => $charges,
      'charges_countLinks' => $data->lastPage(),
    ];
  }
}
