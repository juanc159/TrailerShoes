<?php

namespace App\Http\Controllers;

use App\Http\Requests\DynamicMenuPage\DynamicMenuPageRequest;
use App\Http\Resources\DynamicMenuPage\DynamicMenuPageFormResource;
use App\Http\Resources\DynamicMenuPage\DynamicMenuPageListResource;
use App\Http\Resources\Event\EventListResource;
use App\Repositories\CalendarTypeRepository;
use App\Repositories\DynamicMenuPageRepository;
use App\Repositories\EventRepository;
use App\Repositories\FormRepository;
use App\Repositories\SocialNetworkRepository;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DynamicMenuPageController extends Controller
{
    private $dynamicMenuPageRepository;

    private $formRepository;

    private $socialNetworkRepository;
    private $eventRepository;
    private $calendarTypeRepository;

    public function __construct(DynamicMenuPageRepository $dynamicMenuPageRepository, FormRepository $formRepository, SocialNetworkRepository $socialNetworkRepository, EventRepository $eventRepository, CalendarTypeRepository $calendarTypeRepository)
    {
        $this->dynamicMenuPageRepository = $dynamicMenuPageRepository;
        $this->formRepository = $formRepository;
        $this->socialNetworkRepository = $socialNetworkRepository;
        $this->eventRepository = $eventRepository;
        $this->calendarTypeRepository = $calendarTypeRepository;
    }

    public function list(Request $request)
    {
        $data = $this->dynamicMenuPageRepository->list($request->all());
        $menu = DynamicMenuPageListResource::collection($data);

        return [
            'menu' => $menu,
            'totalData' => $data->total(),
            'totalPage' => $data->perPage(),
            'currentPage' => $data->currentPage(),
        ];
    }

    public function store(DynamicMenuPageRequest $request)
    {
        try {
            DB::beginTransaction();
            if (empty($request->input('id'))) {
                $request['metaData'] = json_encode([
                    [
                        'id' => 1,
                        'order' => 1,
                        'name' => 1,
                        'arrayRows' => [
                            [
                                'id' => 1,
                                'order' => 1,
                                'name' => 'Fila 1',
                                'columns' => [
                                    [
                                        'id' => 1,
                                        'order' => 1,
                                        'classCol' => '12',
                                        'contentText' => null,
                                        'contentImage' => null,
                                        'contentSlider' => [],
                                        'keySlider' => 1,
                                        'contentPopUps' => null,
                                        'contentForm' => null,
                                        'contentSocialNetwork' => null,
                                        'contentCalendar' => null,
                                    ],
                                ],
                            ],
                        ],
                    ],
                ]);
            }

            if ($request->input('principal')) {
                $list = $this->dynamicMenuPageRepository->list(['typeData' => 'all']);
                $list->each(function ($value) {
                    $this->dynamicMenuPageRepository->changeState($value->id, 0, 'principal');
                });
            }

            $dataOld = $this->dynamicMenuPageRepository->find($request->input('id'));
            $dataNew = $data = $this->dynamicMenuPageRepository->store($request);

            $msg = 'agregado';
            $action = 'create';
            if (!empty($request['id'])) {
                $action = 'update';
                $msg = 'modificado';
            }
            logInfo($action, 'Menu Dinamico', 'Menu Dinamico ' . $data->id . ' ' . $msg . ' correctamente', $dataOld, $dataNew);
            DB::commit();

            return response()->json(['code' => 200, 'message' => 'Registro ' . $msg . ' correctamente', 'data' => $data]);
        } catch (Exception $th) {
            DB::rollBack();

            return response()->json(['code' => 500, 'message' => $th->getMessage()], 500);
        }

        return $this->dynamicMenuPageRepository->store($request);
    }

    public function delete($id)
    {
        try {
            DB::beginTransaction();
            $dataOld = $data = $this->dynamicMenuPageRepository->find($id);
            if ($data) {
                $dataNew = $this->dynamicMenuPageRepository->delete($id);
                $msg = 'Registro eliminado correctamente';
                logInfo('delete', 'Menu Dinamico', 'Menu Dinamico ' . $data->id . ' eliminado correctamente', $dataOld, $dataNew);
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
            $data = $this->dynamicMenuPageRepository->find($id);
            $data = new DynamicMenuPageFormResource($data);
            if ($data) {
                $msg = 'El registro existe';
            } else {
                $msg = 'El registro no existe';
            }

            return response()->json([
                'code' => 200,
                'message' => $msg,
                'data' => $data,
            ]);
        } catch (Exception $th) {
            return response()->json(['code' => 500, 'message' => $th->getMessage()], 500);
        }
    }

    public function changeState(Request $request)
    {
        try {
            DB::beginTransaction();

            $dataOld = $this->dynamicMenuPageRepository->find($request->input('id'));
            $dataNew = $model = $this->dynamicMenuPageRepository->changeState($request->input('id'), $request->input('state'), 'state');

            ($model->state == 1) ? $msg = 'Activado' : $msg = 'Inactivado';
            logInfo('cahngeState', 'usuarios', 'Usuario ' . $model->id . ' ' . $msg . ' correctamente', $dataOld, $dataNew);
            DB::commit();

            return response()->json(['code' => 200, 'msg' => $msg . ' con éxito']);
        } catch (Exception $th) {
            DB::rollback();

            return response()->json(['code' => 500, 'msg' => $th->getMessage()]);
        }
    }

    public function infoPage($id)
    {
        try {
            $forms = $this->formRepository->listSelect(['requirement_type_id_null' => true]);

            $formsRequerimentsInternal = $this->formRepository->listSelect([
                'requirement_type_id_null' => false,
                'external' => 1,
            ]);

            $socialNetworks = $this->socialNetworkRepository->listSelect();
            $data = $this->dynamicMenuPageRepository->find($id, select: ['id', 'metaData']);
            if ($data) {
                $msg = 'El registro existe';
            } else {
                $msg = 'El registro no existe';
            }

            return response()->json([
                'code' => 200,
                'data' => $data,
                'message' => $msg,
                'forms' => $forms,
                'formsRequerimentsInternal' => $formsRequerimentsInternal,
                'socialNetworks' => $socialNetworks,
            ]);
        } catch (Exception $th) {

            return response()->json(['code' => 500, 'message' => $th->getMessage()], 500);
        }
    }

    public function storePage(Request $request)
    {
        try {
            DB::beginTransaction();

            $dataOld = $this->dynamicMenuPageRepository->find($request->input("id"));

            $data = $this->dynamicMenuPageRepository->store($request);

            logInfo('Editar', 'Pagina Dinamico', 'Pagina Dinamico ' . $data->id . ' actualizado correctamente',$dataOld,$data);
            DB::commit();

            return response()->json(['code' => 200, 'message' => 'Registro actualizado correctamente', 'data' => $data]);
        } catch (Exception $th) {
            DB::rollBack();

            return response()->json(['code' => 500, 'message' => $th->getMessage()], 500);
        }

        return $this->dynamicMenuPageRepository->store($request);
    }

    public function preview(Request $request)
    {
        $request['typeData'] = 'all';
        $request['state'] = 1;
        $data = $this->dynamicMenuPageRepository->list($request->all(), ['children' => function ($query) {
            $query->where('state', 1);
        }]);
        $dynamicMenuPageList = DynamicMenuPageListResource::collection($data);


        $calendarTypes = $this->calendarTypeRepository->listSelect();

        $filterCalendar['typeData'] = 'all';
        $filterCalendar['public'] = 1;
        $events = $this->eventRepository->list($filterCalendar);

        $eventsData = $events->map(function ($value) {
            return  [
                'id' => $value->id,
                'summary' => $value->summary,
                'title' => $value->summary,
                'start' => $value->start,
                'end' => $value->end,
                'calendar_type_id' => $value->calendar_type_id,
                'link' => $value->link,
                'location' => $value->location,
                'description' => $value->description,
                'color' => $value->calendar_type?->color,
            ];
        });
        return [
            'events' => $eventsData,
            'calendarTypes' => $calendarTypes,
            'dynamicMenuPageList' => $dynamicMenuPageList,
        ];
    }

    public function principal(Request $request)
    {
        $data = $this->dynamicMenuPageRepository->principal();

        return $data;
    }
}
