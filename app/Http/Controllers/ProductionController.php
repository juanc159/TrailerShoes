<?php

namespace App\Http\Controllers;

use App\Http\Requests\Production\ProductionStoreRequest;
use App\Http\Resources\Production\ProductionListResource;
use App\Repositories\EmployeeRepository;
use App\Repositories\StyleRepository;
use App\Repositories\ProductionRepository;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductionController extends Controller
{

    private $productionRepository;
    private $styleRepository;
    private $employeeRepository;

    public function __construct(ProductionRepository $productionRepository, StyleRepository $styleRepository, EmployeeRepository $employeeRepository)
    {
        $this->productionRepository = $productionRepository;
        $this->styleRepository = $styleRepository;
        $this->employeeRepository = $employeeRepository;
    }

    public function list(Request $request)
    {
         $data = $this->productionRepository->list($request->all());
         $productions = ProductionListResource::collection($data);

        return [
            'productions' => $productions,
            'lastPage' => $data->lastPage(),
            'totalData' => $data->total(),
            'totalPage' => $data->perPage(),
            'currentPage' => $data->currentPage(),
        ];
    }

    public function store(ProductionStoreRequest $request)
    {
        try {
            DB::beginTransaction();
            $this->productionRepository->find($request->input("id"));
            $data = $this->productionRepository->store($request->all());

            $msg = 'agregado';
            $action = 'create';
            if (!empty($request['id'])) {
                $msg = 'modificado';
                $action = 'update';
            }

            DB::commit();

            return response()->json(['code' => 200, 'message' => 'Registro ' . $msg . ' correctamente', 'data' => $data]);
        } catch (Exception $th) {
            DB::rollBack();
            return response()->json(['code' => 500, 'message' => $th->getMessage(), 'line' => $th->getLine()], 500);
        }
    }

    public function delete($id)
    {
        try {
            DB::beginTransaction();
            $data = $this->productionRepository->find($id);
            if ($data) {
                $this->productionRepository->delete($id);
                $msg = 'Registro eliminado correctamente';
            } else {
                $msg = 'El registro no existe';
            }

            DB::commit();
            return response()->json(['code' => 200, 'message' => $msg]);
        } catch (Exception $th) {
            DB::rollBack();
            return response()->json(['code' => 500, 'message' => $th->getMessage()], 500);
        }
    }

    public function info($id)
    {
        $aReturn = ['code' => 200];
        try {
            $data = $this->productionRepository->find($id, [], ['id',"style_id","employee_id", "cant", "total"]);
            $aReturn['data'] = $data;
            if ($data) {
                $aReturn['message'] = 'El registro si existe';
            } else {
                $aReturn['message'] = 'El registro no existe';
            }
        } catch (\Exception $th) {
            $aReturn['code'] = 500;
            $aReturn['message'] = $th->getMessage();
        }
        return response()->json($aReturn, $aReturn['code']);
    }

    public function dataForm(Request $request)
    {

        $styles = $this->styleRepository->selectList();
        $employees = $this->employeeRepository->selectList();
        return response()->json([
            "styles" => $styles,
            "employees" => $employees,
        ]);
    }

    public function changeState(Request $request)
    {
        $aReturn = ['code' => 200];
        try {
            DB::beginTransaction();
            $nId = $request->input('id');
            $state = $request->input('state');
            $this->productionRepository->find($request->input('id'));
            $model = $this->productionRepository->changeState($nId, $state, 'state');

            ($model->state == 1) ? $aReturn['msg'] = 'Activado' : $aReturn['msg'] = 'Inactivado';

            DB::commit();

            $aReturn['msg'] .= $aReturn['msg'] . ' con éxito';
        } catch (Exception $th) {
            DB::rollback();
            $aReturn['code'] = 500;
            $aReturn['msg'] = $th->getMessage();
        }
        return response()->json($aReturn);
    }
}
