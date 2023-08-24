<?php

namespace App\Http\Controllers;

use App\Http\Requests\DynamicPage\DynamicPageRequest;
use App\Http\Resources\DynamicMenuPage\DynamicMenuPageListResource;
use App\Http\Resources\DynamicPage\DynamicPageListResource;
use App\Repositories\DynamicMenuPageRepository;
use App\Repositories\DynamicPageRepository;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DynamicPageController extends Controller
{
    private $dynamicPageRepository;

    private $dynamicMenuPageRepository;

    public function __construct(DynamicPageRepository $dynamicPageRepository, DynamicMenuPageRepository $dynamicMenuPageRepository)
    {
        $this->dynamicPageRepository = $dynamicPageRepository;
        $this->dynamicMenuPageRepository = $dynamicMenuPageRepository;
    }

    public function list(Request $request)
    {
        $data = $this->dynamicPageRepository->list($request->all());
        $dynamicPage = DynamicPageListResource::collection($data);

        return [
            'dynamicPage' => $dynamicPage,
            'totalData' => $data->total(),
            'totalPage' => $data->perPage(),
            'currentPage' => $data->currentPage(),
        ];
    }

    public function store(DynamicPageRequest $request)
    {
        try {
            DB::beginTransaction();
            $dataOld = $this->dynamicPageRepository->find($request->input("id"));

            $data = $this->dynamicPageRepository->store($request);

            $msg = 'agregado';
            $action = 'create';
            if (! empty($request['id'])) {
                $action = 'update';
                $msg = 'modificado';
            }
            logInfo($action, 'Pagina Dinamica', 'Pagina Dinamica '.$data->id.' '.$msg.' correctamente',$dataOld,$data);
            DB::commit();

            return response()->json(['code' => 200, 'message' => 'Registro '.$msg.' correctamente', 'data' => $data]);
        } catch (Exception $th) {
            DB::rollBack();

            return response()->json(['code' => 500, 'message' => $th->getMessage()], 500);
        }
    }

    public function delete($id)
    {
        try {
            DB::beginTransaction();
            $dataOld = $data = $this->dynamicPageRepository->find($id);
            if ($data) {
                $dataNew = $this->dynamicPageRepository->delete($id);
                $msg = 'Registro eliminado correctamente';
                logInfo('delete', 'Pagina Dinamica', 'Pagina Dinamica '.$data->id.' eliminado correctamente',$dataOld,$dataNew);
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
            $data = $this->dynamicPageRepository->find($id);
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

    public function preview(Request $request)
    {
        $request['typeData'] = 'all';
        $data = $this->dynamicMenuPageRepository->list($request->all(), ['children']);
        $dynamicMenuPageList = DynamicMenuPageListResource::collection($data);

        return [
            'dynamicMenuPageList' => $dynamicMenuPageList,
        ];
    }
}
