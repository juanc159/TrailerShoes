<?php

namespace App\Http\Controllers;

// Repositories
use App\Repositories\CompanyRepository;
// Resources
use App\Http\Resources\Company\CompanyListResource;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CompanyController extends Controller
{

    private $oCompanyRepository;
    public function __construct(CompanyRepository $CompanyRepository)
    {
        $this->oCompanyRepository = $CompanyRepository;
    }

    public function list(Request $request)
    {
        $data = $this->oCompanyRepository->list($request->all());
        $aCompanies = CompanyListResource::collection($data);

        return [
            'dataList' => $aCompanies,
            'lastPage' => $data->lastPage(),
            'totalData' => $data->total(),
            'totalPage' => $data->perPage(),
            'currentPage' => $data->currentPage(),
        ];
    }

    public function store(Request $request)
    {
        $aReturn = ['code' => 200];
        try {

            DB::beginTransaction();
            $dataOld = $this->oCompanyRepository->find($request->input('id'));
            $dataNew = $data = $this->oCompanyRepository->store($request);

            if ($request['id'] != 'null') {
                $action = 'update';
                $aReturn['message'] = 'modificado';
            } else {
                $action = 'create';
                $aReturn['message'] = 'agregado';
            }

            DB::commit();

            $aCompany = $this->oCompanyRepository->find($request['id']);

            $aReturn['data'] = $data;
            $aReturn['aCompany'] = $aCompany;
            $aReturn['message'] = 'Registro ' . $aReturn['message'] . ' correctamente';
        } catch (\Exception $e) {
            DB::rollBack();
            $aReturn['code'] = 500;
            $aReturn['message'] = $e->getMessage();
            $aReturn['line'] = $e->getLine();
        }
        return response()->json($aReturn, $aReturn['code']);
    }

    public function info($id)
    {
        $aReturn = ['code' => 200];
        try {
            $data = $this->oCompanyRepository->find($id, [], ['id', 'name', 'image_icon', 'image_logo', 'image_cover']);
            $aReturn['data'] = $data;
            if ($data) {
                $aReturn['message'] = 'El registro si existe';
            } else {
                $aReturn['message'] = 'El registro no existe';
            }
        } catch (\Exception $th) {
            $aReturn['code'] = 500;
            $aReturn['message'] = $th->getMessage();
        }
        return response()->json($aReturn, $aReturn['code']);
    }
}
