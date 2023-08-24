<?php

namespace App\Http\Controllers;

use App\Http\Requests\Event\EventStoreRequest;
use App\Http\Resources\Event\EventListResource;
use App\Repositories\CalendarTypeRepository;
use App\Repositories\ChargeRepository;
use App\Repositories\EventRepository;
use App\Repositories\EventUserRepository;
use App\Services\MailService;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EventController extends Controller
{
    private $eventRepository;

    private $calendarTypeRepository;
    private $mailService;
    private $eventUserRepository;


    private $chargeRepository;

    public function __construct(
        EventRepository $eventRepository,
        CalendarTypeRepository $calendarTypeRepository,
        ChargeRepository $chargeRepository,
        MailService $mailService,
        EventUserRepository $eventUserRepository
    ) {
        $this->eventRepository = $eventRepository;
        $this->calendarTypeRepository = $calendarTypeRepository;
        $this->chargeRepository = $chargeRepository;
        $this->mailService = $mailService;
        $this->eventUserRepository = $eventUserRepository;
    }

    public function list(Request $request)
    {
        $filterCharges = [
            'typeData' => 'all',
            'state' => 1,
        ];
        $charges = $this->chargeRepository->list($filterCharges, select: ['id', 'id as value', 'name as title']);

        $calendarTypes = $this->calendarTypeRepository->listSelect();
        $request['typeData'] = 'all';
        $events = $this->eventRepository->list($request->all());
        $events = EventListResource::collection($events);
        return [
            'events' => $events,
            'calendarTypes' => $calendarTypes,
            'charges' => $charges,
        ];
    }

    public function store(EventStoreRequest $request)
    {
        try {
            DB::beginTransaction();
            $post = $request->all();

            unset($request['charges']);
            unset($request['guest']);


            $request['start'] = Carbon::parse($request->input('start'))->format('Y-m-d H:i:s');
            $request['end'] = Carbon::parse($request->input('end'))->format('Y-m-d H:i:s');

            $dataOld = $this->eventRepository->find($request->input("id"));

            $event = $this->eventRepository->store($request->all());

            $event->charges()->sync($post["charges"]);

            $charges =  $event->charges->load("users");

            $usersIds = [];
            foreach ($charges as $charge) {
                foreach ($charge->users as $user) {
                    $this->mailService->setEmailTo($user->email);
                    $this->mailService->setView('Mails.EventInvitationMail');
                    $this->mailService->setSubject('Invitacion a evento');
                    $this->mailService->sendMessage([
                        "event_name" => $event->summary
                    ]);
                    $usersIds = [...$usersIds, ...$charge->users->pluck("id")];
                }
            }

            $event->users()->sync($usersIds);


            $msg = 'agregado';
            $action = 'create';
            if (!empty($request['id'])) {
                $msg = 'modificado';
                $action = 'update';
            }
            logInfo($action, 'Evento calendario', 'Evento calendario ' . $event->id . ' ' . $msg . ' correctamente',$dataOld,$event);
            DB::commit();

            // $data = new EventListResource($data);
            return response()->json(['code' => 200, 'message' => 'Registro ' . $msg . ' correctamente', 'data' => $event]);
        } catch (Exception $th) {
            DB::rollBack();

            return response()->json(['code' => 500, 'message' => $th->getMessage(), 'line' => $th->getLine()], 500);
        }
    }

    public function delete($id)
    {
        try {
            DB::beginTransaction();
            // $data = $this->eventRepository->eventGoogle($event_google_id);
            $dataOld = $data = $this->eventRepository->find($id);
            if ($data) {
                $data->users()->detach();
                $dataNew = $data = $this->eventRepository->delete($id);

                $msg = 'Registro eliminado correctamente';
                logInfo('delete', 'Evento calendario', 'Evento calendario ' . $data->id . ' eliminado correctamente',$dataOld,$dataNew);
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
            $data = $this->eventRepository->find($id, []);
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

    public function dataForm(Request $request)
    {
        $request['typeData'] = 'todos';

        return response()->json([]);
    }

    public function changeState(Request $request)
    {
        try {
            DB::beginTransaction();

            $dataOld = $this->calendarTypeRepository->find($request->input('id'));

            $model = $this->eventRepository->changeState($request->input('id'), $request->input('state'), 'state');

            ($model->state == 1) ? $msg = 'Activado' : $msg = 'Inactivado';

            logInfo('changeState', 'Evento calendario', 'Evento calendario ' . $model->id . ' ' . $msg . ' correctamente',$dataOld,$model);
            DB::commit();

            return response()->json(['code' => 200, 'msg' => $msg . ' con éxito']);
        } catch (Exception $th) {
            DB::rollback();

            return response()->json(['code' => 500, 'msg' => $th->getMessage()]);
        }
    }

    public function guestsInformation(Request $request)
    {
        try {
            $event = $this->eventRepository->find($request["event_id"], ["users.charge"]);
            $guests = $event->users->where("charge_id", $request["charge_id"])->map(function($value){
                return [
                    "id"=>$value->id,
                    "full_name"=>$value->name. ' '. $value->lastName,
                    "accept_invitation"=>$value->pivot->accept_invitation,
                ];
            })
            ->groupBy("accept_invitation");


            return response()->json(['code' => 200, 'guests' => $guests]);
        } catch (Exception $th) {
            return response()->json(['code' => 500, 'message' => $th->getMessage()], 500);
        }
    }
    public function guestanswerInvitation(Request $request)
    {
        try {
            $data = $this->eventUserRepository->searchGuest($request->all());
            $data->accept_invitation = $request->input("answer");
            $data->save();


            return response()->json(['code' => 200, 'data' => $data]);
        } catch (Exception $th) {
            return response()->json(['code' => 500, 'message' => $th->getMessage()], 500);
        }
    }
}
