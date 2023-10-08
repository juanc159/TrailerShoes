<?php

namespace App\Http\Controllers;

use App\Http\Requests\Style\StyleStoreRequest;
use App\Http\Resources\Style\StyleListResource;
use App\Http\Resources\Style\StyleListSelect2Resource;
use App\Repositories\ChargeRepository;
use App\Repositories\StyleRepository;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StyleController extends Controller{

  private $styleRepository;
  private $chargeRepository;

  public function __construct(StyleRepository $styleRepository, ChargeRepository $chargeRepository){
    $this->styleRepository = $styleRepository;
    $this->chargeRepository = $chargeRepository;
  }

  public function list(Request $request){
    $data = $this->styleRepository->list($request->all());
    $styles = StyleListResource::collection($data);

    return [
      'styles' => $styles,
      'lastPage' => $data->lastPage(),
      'totalData' => $data->total(),
      'totalPage' => $data->perPage(),
      'currentPage' => $data->currentPage(),
    ];
  }

  public function store(StyleStoreRequest $request){
    try {
      DB::beginTransaction();
       $this->styleRepository->find($request->input("id"));
      $data = $this->styleRepository->store($request->all());

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
     $data = $this->styleRepository->find($id);
      if( $data ){
        $this->styleRepository->delete($id);
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
      $data = $this->styleRepository->find($id, [], ['id', 'name',"price","charge_id"]);
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

    $charges = $this->chargeRepository->selectList();
    return response()->json([
        "charges" => $charges,
    ]);
  }

  public function changeState(Request $request){
    $aReturn = ['code' => 200];
    try{
      DB::beginTransaction();
      $nId = $request->input('id');
      $state = $request->input('state');
      $this->styleRepository->find($request->input('id'));
       $model = $this->styleRepository->changeState($nId, $state, 'state');

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
    $data = $this->styleRepository->list(request: $request->all());
    $styles = StyleListSelect2Resource::collection($data);

    return [
      'styles_arrayInfo' => $styles,
      'styles_countLinks' => $data->lastPage(),
    ];
  }
}
