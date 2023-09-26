<?php

namespace App\Http\Controllers;

use App\Http\Requests\Role\RoleRequest;
use App\Http\Resources\Role\RoleListResource;
use App\Repositories\MenuRepository;
use App\Repositories\RoleRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Throwable;

class RoleController extends Controller
{
    private $roleRepository;

    private $menuRepository;

    public function __construct(RoleRepository $roleRepository, MenuRepository $menuRepository)
    {
        $this->roleRepository = $roleRepository;
        $this->menuRepository = $menuRepository;
    }

    public function list(Request $request)
    {
        $data = $this->roleRepository->list($request->all());
        $roles = RoleListResource::collection($data);

        return [
            'roles' => $roles,
            'lastPage' => $data->lastPage(),
            'totalData' => $data->total(),
            'totalPage' => $data->perPage(),
            'currentPage' => $data->currentPage(),
        ];
    }

    public function dataForm()
    {
        $menus = $this->menuRepository->list(['typeData' => 'todos'], ['permissions']);

        return response()->json(['menus' => $menus]);
    }

    public function store(RoleRequest $request)
    {
        try {
            DB::beginTransaction();
             $this->roleRepository->find($request->input("id"));
            $data = $this->roleRepository->store($request);
            DB::commit();

            $msg = 'agregado';
            $action = 'create';
            if (! empty($request['id'])) {
                $msg = 'modificado';
                $action = 'update';
            }

            return response()->json(['code' => 200, 'message' => 'Registro '.$msg.' correctamente', 'data' => $data]);
        } catch (Throwable $th) {
            DB::rollBack();

            return response()->json(['code' => 500, 'message' => $th->getMessage()], 500);
        }

        return $this->roleRepository->store($request);
    }

    public function delete($id)
    {
        try {
            DB::beginTransaction();
            $data = $this->roleRepository->find($id);
            if ($data) {
                 $this->roleRepository->delete($id);
                $msg = 'Registro eliminado correctamente';
            } else {
                $msg = 'El registro no existe';
            }
            DB::commit();

            return response()->json(['code' => 200, 'message' => $msg]);
        } catch (Throwable $th) {
            DB::rollBack();

            return response()->json(['code' => 500, 'message' => $th->getMessage()], 500);
        }
    }

    public function info($id)
    {
        try {
            DB::beginTransaction();
            $data = $this->roleRepository->find($id, ['permissions:id'], ['id', 'name', 'description',"pageInitial"]);
            if ($data) {
                $info['id'] = $data->id;

                $info['name'] = $data->name;
                $info['description'] = $data->description;
                $info['pageInitial'] = $data->pageInitial;
                $info['permissions'] = $data->permissions->pluck('id');
                $msg = 'Registro encontrado con éxito';
            } else {
                $msg = 'El registro no existe';
            }
            DB::commit();

            return response()->json(['code' => 200, 'data' => $info, 'message' => $msg]);
        } catch (Throwable $th) {
            DB::rollBack();

            return response()->json(['code' => 500, 'message' => $th->getMessage()], 500);
        }
    }
}
