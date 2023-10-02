<?php

namespace App\Http\Controllers;

use App\Http\Requests\User\UserStoreRequest;
use App\Http\Resources\Charge\ChargeListSelect2Resource;
use App\Http\Resources\IdentityType\IdentityTypeListResource;
use App\Http\Resources\Role\RoleListResource;
use App\Http\Resources\User\UserFormResource;
use App\Http\Resources\User\UserListResource;
use App\Http\Resources\User\UserListSelect2Resource;
use App\Repositories\ChargeRepository;
use App\Repositories\CivilStatusRepository;
use App\Repositories\GenderRepository;
use App\Repositories\IdentityTypeRepository;
use App\Repositories\RoleRepository;
use App\Repositories\UserRepository;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class Usercontroller extends Controller
{
    private $userRepository;

    private $roleRepository;

    private $identityTypeRepository;

    private $chargeRepository;
    private $genderRepository;
    private $civilStatusRepository;

    public function __construct(
        UserRepository $userRepository,
        RoleRepository $roleRepository,
        IdentityTypeRepository $identityTypeRepository,
        ChargeRepository $chargeRepository,
        GenderRepository $genderRepository,
        CivilStatusRepository $civilStatusRepository,
    ) {
        $this->userRepository = $userRepository;
        $this->roleRepository = $roleRepository;
        $this->identityTypeRepository = $identityTypeRepository;
        $this->chargeRepository = $chargeRepository;
        $this->genderRepository = $genderRepository;
        $this->civilStatusRepository = $civilStatusRepository;
    }

    public function list(Request $request)
    {
        $data = $this->userRepository->list($request->all(), ['role']);
        $user = UserListResource::collection($data);

        return [
            'user' => $user,
            'lastPage' => $data->lastPage(),
            'totalData' => $data->total(),
            'totalPage' => $data->perPage(),
            'currentPage' => $data->currentPage(),
        ];
    }

    public function store(UserStoreRequest $request)
    {
        try {
            DB::beginTransaction();
            $this->userRepository->find($request->input("id"));
            $data = $this->userRepository->store($request->all());

            DB::commit();

            $msg = 'agregado';
            $action = 'create';
            if (!empty($request['id'])) {
                $msg = 'modificado';
                $action = 'update';
            }

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
            $data = $this->userRepository->find($id);
            if ($data) {
                $this->userRepository->delete($id);
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
        try {
            $data = $this->userRepository->find($id);
            if ($data) {
                $data = new UserFormResource($data);
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
        $data = $this->roleRepository->list($request->all());
        $roles = RoleListResource::collection($data);

        $identityTypes = $this->identityTypeRepository->list($request->all());
        $identityTypes = IdentityTypeListResource::collection($identityTypes);

        $data = $this->chargeRepository->list();
        $charges = ChargeListSelect2Resource::collection($data);

        $genders = $this->genderRepository->list(request: ["typeData" => "all"], select: ["name as title", "id as value"]);
        $civilStatus = $this->civilStatusRepository->list(request: ["typeData" => "all"], select: ["name as title", "id as value"]);

        return response()->json([
            'roles' => $roles,
            'identityTypes' => $identityTypes,
            'charges_arrayInfo' => $charges,
            'charges_countLinks' => $data->lastPage(),
            'genders' => $genders,
            'civilStatus' => $civilStatus,
        ]);
    }

    public function changeState(Request $request)
    {
        try {
            DB::beginTransaction();

            $this->userRepository->find($request->input('id'));
            $model = $this->userRepository->changeState($request->input('id'), $request->input('state'), 'state');

            ($model->state == 1) ? $msg = 'Activado' : $msg = 'Inactivado';
            DB::commit();

            return response()->json(['code' => 200, 'msg' => $msg . ' con éxito']);
        } catch (Exception $th) {
            DB::rollback();

            return response()->json(['code' => 500, 'msg' => $th->getMessage()]);
        }
    }

    public function select2InfiniteList(Request $request)
    {
        $data = $this->userRepository->list(request: $request->all());
        $users = UserListSelect2Resource::collection($data);

        return [
            'users_arrayInfo' => $users,
            'users_countLinks' => $data->lastPage(),
        ];
    }
}
