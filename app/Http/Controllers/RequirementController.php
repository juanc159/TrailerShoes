<?php

namespace App\Http\Controllers;

use App\Http\Requests\Requirement\RequirementStoreRequest;
use App\Http\Resources\Requirement\RequirementFormResource;
use App\Http\Resources\Requirement\RequirementListResource;
use App\Http\Resources\RequirementType\RequirementTypeListSelect2Resource;
use App\Repositories\FormAnswerRepository;
use App\Repositories\RequirementRepository;
use App\Repositories\RequirementTypeRepository;
use App\Repositories\UserRepository;
use App\Services\MailService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RequirementController extends Controller
{
    private $requirementRepository;

    private $requirementTypeRepository;

    private $userRepository;

    private $mailService;

    private $formAnswerRepository;

    public function __construct(
        RequirementRepository $requirementRepository,
        RequirementTypeRepository $requirementTypeRepository,
        UserRepository $userRepository,
        MailService $mailService,
        FormAnswerRepository $formAnswerRepository
    ) {
        $this->requirementRepository = $requirementRepository;
        $this->requirementTypeRepository = $requirementTypeRepository;
        $this->userRepository = $userRepository;
        $this->mailService = $mailService;
        $this->formAnswerRepository = $formAnswerRepository;
    }

    public function list(Request $request)
    {

        $data = $this->requirementRepository->list($request->all());
        $requirements = RequirementListResource::collection($data);

        return [
            'requirements' => $requirements,
            'lastPage' => $data->lastPage(),
            'totalData' => $data->total(),
            'totalPage' => $data->perPage(),
            'currentPage' => $data->currentPage(),
        ];
    }

    public function store(RequirementStoreRequest $request)
    {
        try {
            DB::beginTransaction();

            $requirementType = $this->requirementTypeRepository->find($request->input('requirement_type_id'), ['charges']);
            if (count($requirementType->charges) > 0) {
                foreach ($request->all() as $key => $value) {
                    $req[$key] = $value == 'null' ? null : $value;
                }
                $post = [
                    'id' => $req['id'] == 'null' ? null : $req['id'],
                    'requirement_type_id' => $request->input('requirement_type_id'),
                    'user_id' => $request->input('user_id'),
                    'charge_id' => $requirementType->charges->first()->id,
                ];

                $dataOld = $this->requirementRepository->find($request->input('id'));
                $requirement = $this->requirementRepository->store($post);

                for ($i = 0; $i < $req['cant_inputs']; $i++) {
                    $dataSave = [
                        'user_id' => $requirement->user_id,
                        'form_id' => $requirement->type->form->id,
                        'form_input_id' => $req['input_id'.$i],
                        'requirement_id' => $requirement->id,
                        'answer' => $req['input_answer'.$i] ?? 'NULL',
                    ];

                    $formAnswer = $this->formAnswerRepository->store($dataSave);
                    if ($request->file('input_answer'.$i)) {
                        $file = $request->file('input_answer'.$i);
                        $path = $request->root().'/storage/'.$file->store('users/user_'.$requirement->user_id.'/'.'requirements/requirement_'.$formAnswer->requirement_id.$request->input('input_answer'.$i), 'public');
                        $formAnswer->answer = $path;
                        $formAnswer->save();
                    }
                    logInfo("create", 'Form Answer', 'Form Answer '.$formAnswer->id.' creado correctamente',null,$formAnswer);

                }

                $msg = 'agregado';
                $action = 'create';
                if (! empty($request['id'])) {
                    $action = 'update';
                    $msg = 'modificado';
                }

                $requirementType = $this->requirementTypeRepository->find($requirement->requirement_type_id, ['charges']);
                $charge = $requirementType->charges()->first();

                $filter['typeData'] = 'todos';
                $filter['charge_id'] = $charge->id;
                $users = $this->userRepository->list($filter);
                foreach ($users as $key => $value) {
                    $this->mailService->setEmailTo($value->email);
                    $this->mailService->setView('Mails.RequirementRegister');
                    $this->mailService->setSubject('Registro de Requerimiento');
                    $this->mailService->sendMessage();
                }

                logInfo($action, 'permisos', 'Permiso '.$requirement->id.' '.$msg.' correctamente',$dataOld,$requirement);
            } else {
                return response()->json(['code' => 200, 'message' => 'Este tipo de requerimiento no tiene cargos asociados']);
            }
            DB::commit();

            return response()->json(['code' => 200, 'message' => 'Registro '.$msg.' correctamente', 'data' => $requirement]);
        } catch (Exception $th) {
            DB::rollBack();

            return response()->json(['code' => 500, 'message' => $th->getMessage(), 'line' => $th->getLine()], 500);
        }
    }

    public function delete($id)
    {
        try {
            DB::beginTransaction();
            $dataOld = $data = $this->requirementRepository->find($id);
            if ($data) {
                $dataNew = $this->requirementRepository->delete($id);
                $msg = 'Registro eliminado correctamente';
                logInfo('delete', "Requerimiento", 'Requerimiento '.$data->id.' eliminado correctamente',$dataOld,$dataNew);
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
            $data = $this->requirementRepository->find($id);
            if ($data) {
                $data = new RequirementFormResource($data);
                $msg = 'El registro si existe';
            } else {
                $msg = 'El registro no existe';
            }

            return response()->json(['code' => 200, 'data' => $data, 'message' => $msg]);
        } catch (Exception $th) {
            return response()->json(['code' => 500, 'message' => $th->getMessage()], 500);
        }
    }

    public function dataForm()
    {
        $data = $this->requirementTypeRepository->list(['internal' => 1]);
        $requirementTypes = RequirementTypeListSelect2Resource::collection($data);

        return [
            'requirementTypes_arrayInfo' => $requirementTypes,
            'requirementTypes_countLinks' => $data->lastPage(),
        ];
    }

    public function changeState(Request $request)
    {
        try {
            DB::beginTransaction();

            $dataOld = $this->requirementRepository->find($request->input('id'));

            $model = $this->requirementRepository->changeState($request->input('id'), $request->input('state_id'), 'state_id');

            ($model->state == 1) ? $msg = 'Activado' : $msg = 'Inactivado';

            logInfo('changeState', "Requerimiento", 'Requerimiento '.$model->id.' eliminado correctamente',$dataOld,$model);

            DB::commit();

            return response()->json(['code' => 200, 'msg' => 'Usuario '.$msg.' con éxito']);
        } catch (Exception $th) {
            DB::rollback();

            return response()->json(['code' => 500, 'msg' => $th->getMessage()]);
        }
    }
}
