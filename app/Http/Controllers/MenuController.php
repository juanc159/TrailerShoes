<?php

namespace App\Http\Controllers;

use App\Http\Requests\Menu\MenuRequest;
use App\Http\Resources\Menu\MenuListResource;
use App\Repositories\MenuRepository;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MenuController extends Controller
{
    private $companyRepository;

    public function __construct(MenuRepository $companyRepository)
    {
        $this->companyRepository = $companyRepository;
    }

    public function list(Request $request)
    {
        $data = $this->companyRepository->list($request->all());
        $menu = MenuListResource::collection($data);

        return [
            'menu' => $menu,
            'totalData' => $data->total(),
            'totalPage' => $data->perPage(),
            'currentPage' => $data->currentPage(),
        ];
    }

    public function store(MenuRequest $request)
    {
        try {
            DB::beginTransaction();

            $data = $this->companyRepository->store($request);

            $msg = 'agregado';
            $action = 'create';
            if (! empty($request['id'])) {
                $action = 'update';
                $msg = 'modificado';
            }

            DB::commit();

            return response()->json(['code' => 200, 'message' => 'Registro '.$msg.' correctamente', 'data' => $data]);
        } catch (Exception $th) {
            DB::rollBack();

            return response()->json(['code' => 500, 'message' => $th->getMessage()], 500);
        }

        return $this->companyRepository->store($request);
    }

    public function delete($id)
    {
        try {
            DB::beginTransaction();
             $data = $this->companyRepository->find($id);
            if ($data) {
                 $this->companyRepository->delete($id);
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
            $data = $this->companyRepository->find($id);
            if ($data) {
                $msg = 'El registro existe';
            } else {
                $msg = 'El registro no existe';
            }
            DB::commit();

            return response()->json(['code' => 200, 'data' => $data, 'message' => $msg]);
        } catch (Exception $th) {
            DB::rollBack();

            return response()->json(['code' => 500, 'message' => $th->getMessage()], 500);
        }
    }
}
