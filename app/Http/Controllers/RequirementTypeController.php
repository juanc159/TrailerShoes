<?php

namespace App\Http\Controllers;

use App\Http\Requests\RequirementType\RequirementTypeStoreRequest;
use App\Http\Resources\Charge\ChargeListSelect2Resource;
use App\Http\Resources\RequirementType\RequirementTypeFormResource;
use App\Http\Resources\RequirementType\RequirementTypeListResource;
use App\Http\Resources\RequirementType\RequirementTypeListSelect2Resource;
use App\Repositories\ChargeRepository;
use App\Repositories\FormRepository;
use App\Repositories\RequirementTypeRepository;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RequirementTypeController extends Controller
{
    private $requirementTypeRepository;

    private $chargeRepository;

    private $formRepository;

    public function __construct(RequirementTypeRepository $requirementTypeRepository, ChargeRepository $chargeRepository, FormRepository $formRepository)
    {
        $this->requirementTypeRepository = $requirementTypeRepository;
        $this->chargeRepository = $chargeRepository;
        $this->formRepository = $formRepository;
    }

    public function list(Request $request)
    {
        $data = $this->requirementTypeRepository->list($request->all());
        $requirementTypes = RequirementTypeListResource::collection($data);

        return [
            'requirementTypes' => $requirementTypes,
            'lastPage' => $data->lastPage(),
            'totalData' => $data->total(),
            'totalPage' => $data->perPage(),
            'currentPage' => $data->currentPage(),
        ];
    }

    public function store(RequirementTypeStoreRequest $request)
    {
        try {
            DB::beginTransaction();

            $postRequirementType = $request->all();
            unset($postRequirementType['arrayInputs']);
            unset($postRequirementType['form_id']);

            $postRequirementType['internal'] = $postRequirementType['internal'] ?? 0;
            $postRequirementType['external'] = $postRequirementType['external'] ?? 0;

            $dataOld = $this->requirementTypeRepository->find($request->input('id'));
            $requirementType = $this->requirementTypeRepository->store($postRequirementType);
            $postForm = [
                'id' => $request->input('form_id'),
                'requirement_type_id' => $requirementType->id,
                'name' => $request->input('name'),
            ];

            $this->formRepository->store($postForm, $request->input('arrayInputs'));

            DB::commit();

            $msg = 'agregado';
            $action = 'create';
            if (! empty($request['id'])) {
                $msg = 'modificado';
                $action = 'update';
            }
            logInfo($action, 'tipo de requerimiento', 'Tipo de requerimiento '.$requirementType->id.' '.$msg.' correctamente',$dataOld,$requirementType);

            return response()->json(['code' => 200, 'message' => 'Registro '.$msg.' correctamente', 'data' => $requirementType]);
        } catch (Exception $th) {
            DB::rollBack();

            return response()->json(['code' => 500, 'message' => $th->getMessage(), 'line' => $th->getLine()], 500);
        }
    }

    public function delete($id)
    {
        try {
            DB::beginTransaction();
            $dataOld =$data = $this->requirementTypeRepository->find($id);
            if ($data) {
                $dataNew = $this->requirementTypeRepository->delete($id);
                $msg = 'Registro eliminado correctamente';
                logInfo('delete', 'tipo de requerimiento', 'Tipo de requerimiento '.$data->id.' eliminado correctamente',$dataOld,$dataNew);
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
            $data = $this->requirementTypeRepository->find($id, ['charges']);
            $data = new RequirementTypeFormResource($data);
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
        $data = $this->chargeRepository->list(request: $request->all());
        $charges = ChargeListSelect2Resource::collection($data);

        return [
            'charges_arrayInfo' => $charges,
            'charges_countLinks' => $data->lastPage(),
        ];
    }

    public function changeState(Request $request)
    {
        try {
            DB::beginTransaction();

            $model = $this->requirementTypeRepository->changeState($request->input('id'), $request->input('state'), 'state');

            ($model->state == 1) ? $msg = 'Activado' : $msg = 'Inactivado';

            logInfo('changeState', 'tipo de requerimiento', 'Tipo de requerimiento '.$model->id.' '.$msg.' correctamente');
            DB::commit();

            return response()->json(['code' => 200, 'msg' => $msg.' con éxito']);
        } catch (Exception $th) {
            DB::rollback();

            return response()->json(['code' => 500, 'msg' => $th->getMessage()]);
        }
    }

    public function select2InfiniteList(Request $request)
    {
        $data = $this->requirementTypeRepository->list(request: $request->all());
        $requirementTypes = RequirementTypeListSelect2Resource::collection($data);

        return [
            'requirementTypes_arrayInfo' => $requirementTypes,
            'requirementTypes_countLinks' => $data->lastPage(),
        ];
    }
}
