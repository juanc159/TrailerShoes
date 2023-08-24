<?php

namespace App\Http\Controllers;

use App\Http\Requests\SocialNetwork\SocialNetworkStoreRequest;
use App\Http\Resources\SocialNetwork\SocialNetworkFormResource;
use App\Http\Resources\SocialNetwork\SocialNetworkListResource;
use App\Repositories\SocialNetworkRepository;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SocialNetworkController extends Controller
{
    private $socialNetworkRepository;

    public function __construct(SocialNetworkRepository $socialNetworkRepository)
    {
        $this->socialNetworkRepository = $socialNetworkRepository;
    }

    public function list(Request $request)
    {
        $data = $this->socialNetworkRepository->list($request->all());
        $socialNetworks = SocialNetworkListResource::collection($data);

        return [
            'socialNetworks' => $socialNetworks,
            'lastPage' => $data->lastPage(),
            'totalData' => $data->total(),
            'totalPage' => $data->perPage(),
            'currentPage' => $data->currentPage(),
        ];
    }

    public function store(SocialNetworkStoreRequest $request)
    {
        try {
            DB::beginTransaction();
            $post = $request;
            unset($request['path']);
            //limpia el request del objeto formData JS
            foreach ($request->all() as $key => $value) {
                $data[$key] = $request[$key] != 'null' ? $request[$key] : null;
            }
            $request = new Request($data);

            $dataOld = $this->socialNetworkRepository->find($request->input("id"));
            $socialNetwork = $this->socialNetworkRepository->store($request->all());

            if ($post->file('path')) {
                $file = $post->file('path');
                $path = $post->root().'/storage/'.$file->store('socialNetwork/'.$socialNetwork->id.$post->input('path'), 'public');
                $socialNetwork->path = $path;
                $socialNetwork->save();
            }

            $msg = 'agregado';
            $action = 'create';
            if (! empty($request['id'])) {
                $msg = 'modificado';
                $action = 'update';
            }
            logInfo($action, 'redes sociales', 'Red social '.$socialNetwork->id.' '.$msg.' correctamente',$dataOld,$socialNetwork);
            DB::commit();

            return response()->json(['code' => 200, 'message' => 'Registro '.$msg.' correctamente', 'data' => $socialNetwork]);
        } catch (Exception $th) {
            DB::rollBack();

            return response()->json(['code' => 500, 'message' => $th->getMessage(), 'line' => $th->getLine()], 500);
        }
    }

    public function delete($id)
    {
        try {
            DB::beginTransaction();
            $dataOld = $data = $this->socialNetworkRepository->find($id);
            if ($data) {
                $dataNew = $this->socialNetworkRepository->delete($id);
                $msg = 'Registro eliminado correctamente';
                logInfo('delete', 'redes sociales', 'Red social '.$data->id.' eliminado correctamente',$dataOld,$dataNew);
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
            $data = $this->socialNetworkRepository->find($id);
            $data = new SocialNetworkFormResource($data);
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
}
