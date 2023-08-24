<?php

namespace App\Http\Controllers;

use App\Http\Requests\Authentication\PassportAuthLoginRequest;
use App\Http\Requests\Authentication\PassportAuthRegisterRequest;
use App\Http\Resources\IdentityType\IdentityTypeListResource;
use App\Services\MailService;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
// Repositories
use App\Repositories\MenuRepository;
use App\Repositories\UserRepository;
use App\Repositories\CompanyRepository;
use App\Repositories\IdentityTypeRepository;

class PassportAuthController extends Controller{

    private $mailService;
    private $userRepository;
    private $menuRepository;
    private $oCompanyRepository;
    private $identityTypeRepository;

    public function __construct(CompanyRepository $CompanyRepository, UserRepository $userRepository, MenuRepository $menuRepository, MailService $mailService, IdentityTypeRepository $identityTypeRepository)
    {
        $this->userRepository = $userRepository;
        $this->oCompanyRepository = $CompanyRepository;
        $this->menuRepository = $menuRepository;
        $this->mailService = $mailService;
        $this->identityTypeRepository = $identityTypeRepository;
    }

    public function register(PassportAuthRegisterRequest $request)
    {
        try {
            DB::beginTransaction();
            $user = $this->userRepository->register($request->all());

            $this->mailService->setEmailTo($request->input('email'));
            $this->mailService->setView('Mails.UserRegister');
            $this->mailService->setSubject('Registro de usuario');
            $this->mailService->sendMessage();

            $data = [
                'email' => $request->email,
                'password' => $request->password,
            ];

            auth()->attempt($data);

            logInfo('register', 'usuario', 'Usuario '.$user->id.' registrado correctamente');
            DB::commit();

            return response()->json(['code' => 200, 'message' => 'Registro agregado correctamente', 'data' => $user]);
        } catch (Exception $th) {
            DB::rollBack();

            return response()->json(['code' => 500, 'message' => $th->getMessage(), 'line' => $th->getLine()], 500);
        }
    }

    public function login(PassportAuthLoginRequest $request)
    {
        $data = [
            'email' => $request->email,
            'password' => $request->password,
        ];

        if (auth()->attempt($data)) {
            $user = Auth::user();
            $obj['id'] = $user->id;
            $obj['email'] = $user->email;
            $obj['name'] = $user->name;
            $obj['lastName'] = $user->lastName;
            $obj['role_name'] = $user->role?->name;
            $obj['pageInitial'] = $user->role?->pageInitial;
            $obj['role_id'] = $user->role?->id;
            $obj['charge_name'] = $user->charge?->name;
            $obj['charge_id'] = $user->charge?->id;
            $obj['identity_type_id'] = $user->identity_type_id;
            $obj['idNumber'] = $user->idNumber;
            $menu = $this->menuRepository->list(['typeData' => 'todos', 'permissions' => $user->all_permissions->pluck('name')], ['children']);
            foreach ($menu as $key => $value) {
                $arrayMenu[$key]['title'] = $value->title;
                $arrayMenu[$key]['to']['name'] = $value->to;
                $arrayMenu[$key]['icon']['icon'] = $value->icon ?? 'mdi-arrow-right-thin-circle-outline';

                if (! empty($value['children'])) {
                    foreach ($value['children'] as $key2 => $value2) {
                        $arrayMenu[$key]['children'][$key2]['title'] = $value2->title;
                        $arrayMenu[$key]['children'][$key2]['to'] = $value2->to;
                        // $arrayMenu[$key]["children"][$key2]["icon"] = $value2->icon ?? "mdi-arrow-right-thin-circle-outline";
                        if (! empty($value2['children'])) {
                            foreach ($value2['children'] as $key3 => $value3) {
                                $arrayMenu[$key]['children'][$key2]['children'][$key3]['title'] = $value3->title;
                                $arrayMenu[$key]['children'][$key2]['children'][$key3]['to'] = $value3->to;
                                // $arrayMenu[$key]["children"][$key2]["children"][$key3]["icon"] = $value3->icon ?? "mdi-arrow-right-thin-circle-outline";
                            }
                        }
                    }
                }
            }
            logInfo('login', 'usuario', 'Usuario '.$user->id.' logeado correctamente');
            $aCompany = $this->oCompanyRepository->find(1);

            return response()->json([
                'token' => $user->createToken('PassportAuth')->accessToken,
                'user' => $obj,
                'permissions' => $user->all_permissions->pluck('name'),
                'menu' => $arrayMenu ?? [],
                'aCompany' => $aCompany,
                'message' => 'Bienvenido',
                'code' => '200',
            ], 200);
        } else {
            return response()->json([
                'code' => '400',
                'message' => 'Incorrect Credentials',
            ], 400);
        }
    }

    public function userInfo()
    {
        $user = Auth::user();

        return response()->json(['user' => $user], 200);
    }

    public function dataForm()
    {
        $request['typeData'] = 'all';
        $identityTypes = $this->identityTypeRepository->list($request);
        $identityTypes = IdentityTypeListResource::collection($identityTypes);

        return response()->json([
            'identityTypes' => $identityTypes,
        ]);
    }
}
