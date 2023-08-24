<?php

namespace App\Http\Controllers;

use App\Http\Requests\CalendarType\CalendarTypeStoreRequest;
use App\Http\Resources\Charge\ChargeListResource;
use App\Repositories\CalendarTypeRepository;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CalendarTypeController extends Controller
{
    private $calendarTypeRepository;

    public function __construct(CalendarTypeRepository $calendarTypeRepository)
    {
        $this->calendarTypeRepository = $calendarTypeRepository;
    }

    public function list(Request $request)
    {
        $data = $this->calendarTypeRepository->list($request->all());
        $calendarTypes = ChargeListResource::collection($data);

        return [
            'calendarTypes' => $calendarTypes,
            'lastPage' => $data->lastPage(),
            'totalData' => $data->total(),
            'totalPage' => $data->perPage(),
            'currentPage' => $data->currentPage(),
        ];
    }

    public function store(CalendarTypeStoreRequest $request)
    {
        try {
            DB::beginTransaction();
            $dataOld = $this->calendarTypeRepository->find($request->input("id"));
            $data = $this->calendarTypeRepository->store($request->all());

            $msg = 'agregado';
            $action = 'create';
            if (! empty($request['id'])) {
                $msg = 'modificado';
                $action = 'update';
            }
            logInfo($action, 'Tipo de calendario', 'Tipo de calendario '.$data->id.' '.$msg.' correctamente',$dataOld,$data);
            DB::commit();

            return response()->json(['code' => 200, 'message' => 'Registro '.$msg.' correctamente', 'data' => $data]);
        } catch (Exception $th) {
            DB::rollBack();

            return response()->json(['code' => 500, 'message' => $th->getMessage(), 'line' => $th->getLine()], 500);
        }
    }

    public function delete($id)
    {
        try {
            DB::beginTransaction();
            $dataOld = $data = $this->calendarTypeRepository->find($id);
            if ($data) {
                $dataNew = $this->calendarTypeRepository->delete($id);
                $msg = 'Registro eliminado correctamente';
                logInfo('delete', 'Tipo de calendario', 'Tipo de calendario '.$data->id.' eliminado correctamente',$dataOld,$dataNew);
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
        try {
            $data = $this->calendarTypeRepository->find($id, [], ['id', 'name',"color"]);
            if ($data) {
                $msg = 'El registro si existe';
            } else {
                $msg = 'El registro no existe';
            }

            return response()->json(['code' => 200, 'data' => $data, 'message' => $msg]);
        } catch (Exception $th) {
            return response()->json(['code' => 500, 'message' => $th->getMessage()], 500);
        }
    }

    public function dataForm(Request $request)
    {
        $request['typeData'] = 'todos';

        return response()->json([
        ]);
    }

    public function changeState(Request $request)
    {
        try {
            DB::beginTransaction();

            $dataOld = $this->calendarTypeRepository->find($request->input('id'));

            $model = $this->calendarTypeRepository->changeState($request->input('id'), $request->input('state'), 'state');

            ($model->state == 1) ? $msg = 'Activado' : $msg = 'Inactivado';

            logInfo('changeState', 'Tipo de calendario', 'Tipo de calendario '.$model->id.' '.$msg.' correctamente',$dataOld,$model);
            DB::commit();

            return response()->json(['code' => 200, 'msg' => $msg.' con éxito']);
        } catch (Exception $th) {
            DB::rollback();

            return response()->json(['code' => 500, 'msg' => $th->getMessage()]);
        }
    }
}
