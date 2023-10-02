<?php

namespace App\Http\Controllers;

use App\Http\Requests\Permission\PermissionStoreRequest;
use App\Http\Resources\Permission\PermissionListResource;
use App\Repositories\MenuRepository;
use App\Repositories\PermissionRepository;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PermissionController extends Controller
{
    private $permissionRepository;

    private $menuRepository;

    public function __construct(PermissionRepository $permissionRepository, MenuRepository $menuRepository)
    {
        $this->permissionRepository = $permissionRepository;
        $this->menuRepository = $menuRepository;
    }

    public function list(Request $request)
    {
        $data = $this->permissionRepository->list($request->all(), ['menu']);
        $permission = PermissionListResource::collection($data);

        return [
            'permission' => $permission,
            'lastPage' => $data->lastPage(),
            'totalData' => $data->total(),
            'totalPage' => $data->perPage(),
            'currentPage' => $data->currentPage(),
        ];
    }

    public function store(PermissionStoreRequest $request)
    {

        try {
            DB::beginTransaction();
            $data = $this->permissionRepository->store($request);
            DB::commit();

            $msg = 'agregado';
            $action = 'create';
            if (! empty($request['id'])) {
                $action = 'update';
                $msg = 'modificado';
            }

            return response()->json(['code' => 200, 'message' => 'Registro '.$msg.' correctamente', 'data' => $data]);
        } catch (Exception $th) {
            DB::rollBack();

            return response()->json(['code' => 500, 'message' => $th->getMessage()], 500);
        }

        return $this->permissionRepository->store($request);
    }

    public function delete($id)
    {
        try {
            DB::beginTransaction();
             $data = $this->permissionRepository->find($id);
            if ($data) {
                 $this->permissionRepository->delete($id);
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
            DB::beginTransaction();
            $data = $this->permissionRepository->find($id);
            DB::commit();

            return response()->json(['code' => 200, 'data' => $data]);
        } catch (Exception $th) {
            DB::rollBack();

            return response()->json(['code' => 500, 'message' => $th->getMessage()], 500);
        }
    }

    public function dataForm()
    {
        $menus = $this->menuRepository->list(['typeData' => 'todos']);

        return response()->json(['menus' => $menus]);
    }
}
