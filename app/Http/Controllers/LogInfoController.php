<?php

namespace App\Http\Controllers;

use App\Http\Requests\LogInfo\LogInfoStoreRequest;
use App\Http\Resources\LogInfo\LogInfoListResource;
use App\Repositories\LogInfoRepository;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LogInfoController extends Controller
{
    private $logInfoRepository;

    public function __construct(
        LogInfoRepository $logInfoRepository
    ) {
        $this->logInfoRepository = $logInfoRepository;
    }

    public function list(Request $request)
    {
        $data = $this->logInfoRepository->list($request->all());
        $logInfos = LogInfoListResource::collection($data);

        return [
            'logInfos' => $logInfos,
            'lastPage' => $data->lastPage(),
            'totalData' => $data->total(),
            'totalPage' => $data->perPage(),
            'currentPage' => $data->currentPage(),
        ];
    }

    public function store(LogInfoStoreRequest $request)
    {
        try {
            DB::beginTransaction();
            $data = $this->logInfoRepository->store($request->all());
            DB::commit();

            $msg = 'agregado';
            if (! empty($request['id'])) {
                $msg = 'modificado';
            }

            return response()->json(['code' => 200, 'message' => 'Registro '.$msg.' correctamente', 'data' => $data]);
        } catch (Exception $th) {
            DB::rollBack();

            return response()->json(['code' => 500, 'message' => $th->getMessage(), 'line' => $th->getLine()], 500);
        }
    }
}
