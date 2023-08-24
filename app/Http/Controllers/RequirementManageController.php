<?php

namespace App\Http\Controllers;

use App\Http\Requests\Requirement\RequirementManageStoreRequest;
use App\Http\Resources\RequirementManage\RequirementManageFormResource;
use App\Repositories\RequirementManageFileRepository;
use App\Repositories\RequirementManageRepository;
use App\Repositories\RequirementRepository;
use App\Repositories\RequirementTypeRepository;
use App\Repositories\UserRepository;
use App\Services\MailService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class RequirementManageController extends Controller
{
    private $requirementRepository;

    private $requirementManageRepository;

    private $requirementManageFileRepository;

    private $userRepository;

    private $requirementTypeRepository;

    private $mailService;

    public function __construct(
        RequirementRepository $requirementRepository,
        RequirementManageRepository $requirementManageRepository,
        RequirementManageFileRepository $requirementManageFileRepository,
        UserRepository $userRepository,
        RequirementTypeRepository $requirementTypeRepository,
        MailService $mailService
    ) {
        $this->requirementRepository = $requirementRepository;
        $this->requirementManageRepository = $requirementManageRepository;
        $this->requirementManageFileRepository = $requirementManageFileRepository;
        $this->userRepository = $userRepository;
        $this->requirementTypeRepository = $requirementTypeRepository;
        $this->mailService = $mailService;
    }

    public function manageDataForm(Request $request)
    {
        try {
            $requirement = $this->requirementRepository->find($request->input('requirement_id'));
            if ($requirement) {
                $msg = 'El registro si existe';
            } else {
                $msg = 'El registro no existe';
            }
            $charges = $requirement->type->charges->map(function ($value) {
                return [
                    'id' => $value->id,
                    'name' => $value->name,
                    'order' => $value->pivot->order,
                ];
            });

            $requirement = new RequirementManageFormResource($requirement);

            return response()->json([
                'code' => 200,
                'message' => $msg,
                'requirement' => $requirement,
                'charges' => $charges,
            ]);
        } catch (Exception $th) {
            return response()->json(['code' => 500, 'message' => $th->getMessage()], 500);
        }
    }

    public function manageStore(RequirementManageStoreRequest $request)
    {
        try {
            DB::beginTransaction();
            $post = $request->all();
            for ($i = 0; $i < $request['countFiles']; $i++) {
                unset($post['file' . $i]);
                unset($post['file_id' . $i]);
                unset($post['file_name' . $i]);
                unset($post['file_delete' . $i]);
            }
            unset($post['countFiles']);
            unset($post['where']);
            unset($post['archive_final']);
            unset($post['action']);
            $post['user_id'] = Auth::user()->id;
            $dataOld = $this->requirementManageRepository->find($request->input("id"));
            $data = $this->requirementManageRepository->store($post);

            $msg = 'agregado';
            $action = 'create';
            if (!empty($request['id'])) {
                $action = 'update';
                $msg = 'modificado';
            }

            for ($i = 0; $i < $request['countFiles']; $i++) {
                $info = [];
                if ($request->input('file_delete' . $i) == 'delete') {
                    $this->requirementManageFileRepository->delete($request->input('file_id' . $i));
                } else {
                    if ($request->file('file' . $i)) {
                        $file = $request->file('file' . $i);
                        $path = $request->root() . '/storage/' . $file->store('/requirementManages/requirementManage_' . $data->id . $request->input('file' . $i), 'public');
                        $info['path'] = $path;
                        $info['name'] = $request->input('file_name' . $i);
                        $info['requirement_manage_id'] = $data->id;
                        $this->requirementManageFileRepository->store($info);
                    }
                }
            }

            $state = 2;
            if ($request->input('action') == 'Finalizar') {
                $state = 3;
            }
            $requirementOld = $this->requirementRepository->find($data->requirement_id);
            $requirementNew = $this->requirementRepository->changeState($data->requirement_id, $state, 'requirement_state_id');

            // archive_final
            if ($request->file('archive_final')) {
                $file = $request->file('archive_final');
                $path = $request->root() . '/storage/' . $file->store('/requirement/requirement_' . $data->id . $request->input('archive_final'), 'public');
                $requirementNew = $this->requirementRepository->changeState($data->requirement_id, $path, 'archive_final');
            }
            if (!empty($request->input('action') && $request->input('action') != 'null' && $request->input('action') != 'undefined' && $request->input('action') != 'Finalizar')) {
                $requirementNew = $this->requirementRepository->changeState($data->requirement_id, $request->input('where'), 'charge_id');
            }
            logInfo($action, 'Requirement', 'Requirement ' . $requirementNew->id . ' ' . $msg . ' correctamente', $requirementOld, $requirementNew);

            $requirement = $this->requirementRepository->find($data->requirement_id);
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

            logInfo($action, 'Requirement Manage', 'Requirement Manage ' . $data->id . ' ' . $msg . ' correctamente', $dataOld, $data);
            DB::commit();

            return response()->json(['code' => 200, 'message' => 'Registro ' . $msg . ' correctamente', 'data' => $data]);
        } catch (Exception $th) {
            DB::rollBack();

            return response()->json(['code' => 500, 'message' => $th->getMessage(), 'line' => $th->getLine()], 500);
        }
    }
}
