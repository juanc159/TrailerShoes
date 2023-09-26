<?php

namespace App\Http\Controllers;

use App\Http\Requests\Loan\LoanStoreRequest;
use App\Http\Resources\Loan\LoanListResource;
use App\Repositories\LoanRepository;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LoanController extends Controller{

  private $loanRepository;

  public function __construct(LoanRepository $loanRepository){
    $this->loanRepository = $loanRepository;
  }

  public function list(Request $request){
    $data = $this->loanRepository->list($request->all());
    $loans = LoanListResource::collection($data);

    return [
      'loans' => $loans,
      'lastPage' => $data->lastPage(),
      'totalData' => $data->total(),
      'totalPage' => $data->perPage(),
      'currentPage' => $data->currentPage(),
    ];
  }

  public function store(LoanStoreRequest $request){
    try {
      DB::beginTransaction();
      $data = $this->loanRepository->store($request->all());

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
      $data = $this->loanRepository->find($id);
      if( $data ){
        $this->loanRepository->delete($id);
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
      $data = $this->loanRepository->find($id, [], ['id', 'name']);
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
      $this->loanRepository->find($request->input('id'));
      $model = $this->loanRepository->changeState($nId, $state, 'state');

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

}
