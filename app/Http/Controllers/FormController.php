<?php

namespace App\Http\Controllers;

use App\Http\Requests\Form\FormStoreRequest;
use App\Http\Resources\Form\FormFormResource;
use App\Http\Resources\Form\FormListResource;
use App\Repositories\FormAnswerRepository;
use App\Repositories\FormRepository;
use App\Repositories\RequirementRepository;
use App\Repositories\RequirementTypeRepository;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FormController extends Controller
{
    private $formRepository;

    private $formAnswerRepository;

    private $requirementTypeRepository;

    private $requirementRepository;

    public function __construct(
        FormRepository $formRepository,
        FormAnswerRepository $formAnswerRepository,
        RequirementTypeRepository $requirementTypeRepository,
        RequirementRepository $requirementRepository
    ) {
        $this->formRepository = $formRepository;
        $this->formAnswerRepository = $formAnswerRepository;
        $this->requirementTypeRepository = $requirementTypeRepository;
        $this->requirementRepository = $requirementRepository;
    }

    public function list(Request $request)
    {
        $request['null_requirement_type_id'] = 1;
        $data = $this->formRepository->list($request->all());
        $forms = FormListResource::collection($data);

        return [
            'forms' => $forms,
            'lastPage' => $data->lastPage(),
            'totalData' => $data->total(),
            'totalPage' => $data->perPage(),
            'currentPage' => $data->currentPage(),
        ];
    }

    public function store(FormStoreRequest $request)
    {
        try {
            DB::beginTransaction();
            $post = $request->all();
            unset($post['arrayInputs']);
            $dataOld = $this->formRepository->find( $request->input('id'));
            $data = $this->formRepository->store($post, $request->input('arrayInputs'));

            $msg = 'agregado';
            $action = 'create';
            if (! empty($request['id'])) {
                $msg = 'modificado';
                $action = 'update';
            }
            logInfo($action, 'Formulario', 'Formulario '.$data->id.' '.$msg.' correctamente',$dataOld,$data);
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
            $dataOld = $data = $this->formRepository->find($id);
            if ($data) {
                $dataNew = $this->formRepository->delete($id);
                $msg = 'Registro eliminado correctamente';
                logInfo('delete', 'Formulario', 'Formulario '.$data->id.' eliminado correctamente',$dataOld,$dataNew);
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
            $data = $this->formRepository->find($id);
            if ($data) {
                $data = new FormFormResource($data);
                $msg = 'El registro si existe';
            } else {
                $msg = 'El registro no existe';
            }

            return response()->json(['code' => 200, 'data' => $data, 'message' => $msg]);
        } catch (Exception $th) {
            return response()->json(['code' => 500, 'message' => $th->getMessage()], 500);
        }
    }

    public function saveAnswer(Request $request)
    {
        try {
            DB::beginTransaction();
            $requirement = null;
            $message = 'Formulario respondido';
            foreach ($request->all() as $key => $value) {
                $req[$key] = $value == 'null' ? null : $value;
            }
            if (! empty($req['requirement_type_id'])) {
                $requirementType = $this->requirementTypeRepository->find($req['requirement_type_id'], ['charges']);
                $post = [
                    'requirement_type_id' => $req['requirement_type_id'],
                    'charge_id' => $requirementType->charges->first()->id,
                ];

                $requirement = $this->requirementRepository->store($post);
                $message = 'Se ha creado un nuevo requerimiento con id '.$requirement->id;
            }

            for ($i = 0; $i < $req['cant_inputs']; $i++) {
                $dataSave = [
                    'user_id' => $requirement?->user_id,
                    'form_id' => $req['form_id'],
                    'form_input_id' => $req['input_id'.$i],
                    'requirement_id' => $requirement?->id,
                    'answer' => $req['input_answer'.$i] ?? null,
                ];


                $formAnswer = $this->formAnswerRepository->store($dataSave);

                if ($request->file('input_answer'.$i)) {
                    $file = $request->file('input_answer'.$i);
                    $path = $request->root().'/storage/'.$file->store('users/user_'.$requirement->user_id.'/'.'requirements/requirement_'.$formAnswer->requirement_id.$request->input('input_answer'.$i), 'public');
                    $formAnswer->answer = $path;
                    $formAnswer->save();
                }

            }

            DB::commit();

            return response()->json(['code' => 200, 'message' => $message]);
        } catch (Exception $th) {
            DB::rollBack();

            return response()->json(['code' => 500, 'message' => $th->getMessage()], 500);
        }
    }
}
