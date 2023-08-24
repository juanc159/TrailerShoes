<?php

namespace App\Http\Controllers;

use App\Http\Requests\Survey\SurveyStoreRequest;
use App\Http\Resources\Survey\SurveyFormResource;
use App\Http\Resources\Survey\SurveyListResource;
use App\Repositories\ChargeRepository;
use App\Repositories\SurveyAnswersRepository;
use App\Repositories\SurveyQuestionOptionRepository;
use App\Repositories\SurveyQuestionRepository;
use App\Repositories\SurveyRepository;
use App\Services\MailService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SurveyController extends Controller
{
    private $surveyRepository;

    private $surveyQuestionRepository;

    private $surveyQuestionOptionRepository;

    private $surveyAnswersRepository;

    private $mailService;

    private $chargeRepository;

    public function __construct(
        SurveyRepository $surveyRepository,
        SurveyQuestionRepository $surveyQuestionRepository,
        SurveyQuestionOptionRepository $surveyQuestionOptionRepository,
        SurveyAnswersRepository $surveyAnswersRepository,
        MailService $mailService,
        ChargeRepository $chargeRepository,
    ) {
        $this->surveyRepository = $surveyRepository;
        $this->surveyQuestionRepository = $surveyQuestionRepository;
        $this->surveyQuestionOptionRepository = $surveyQuestionOptionRepository;
        $this->surveyAnswersRepository = $surveyAnswersRepository;
        $this->mailService = $mailService;
        $this->chargeRepository = $chargeRepository;
    }

    public function list(Request $request)
    {
        $filterCharges = [
            'typeData' => 'all',
            'state' => 1,
        ];
        $charges = $this->chargeRepository->list($filterCharges, select: ['id', 'id as value', 'name as title']);

        $data = $this->surveyRepository->list($request->all());
        $surveys = SurveyListResource::collection($data);

        return [
            'charges' => $charges,
            'surveys' => $surveys,
            'lastPage' => $data->lastPage(),
            'totalData' => $data->total(),
            'totalPage' => $data->perPage(),
            'currentPage' => $data->currentPage(),
        ];
    }

    public function store(SurveyStoreRequest $request)
    {
        try {
            DB::beginTransaction();
            $post = $request->all();
            unset($post['arrayQuestions']);
            $dataOld = $this->surveyRepository->find($request->input("id"));
            $data = $this->surveyRepository->store($post);

            if ($request->input('arrayQuestions') && count($request->input('arrayQuestions')) > 0) {
                foreach ($request->input('arrayQuestions') as $key => $value) {
                    $infoSave = [
                        'id' => $value['id'] ?? null,
                        'survey_id' => $data->id,
                        'question' => $value['question'],
                        'type_question' => $value['type_question'],
                    ];
                    $arrayOptions = $value['arrayOptions'];
                    $surveyQuestion = $this->surveyQuestionRepository->store($infoSave);
                    if ($surveyQuestion->type_question != 1) {
                        foreach ($arrayOptions as $key2 => $value2) {
                            $infoSave = [
                                'id' => $value2['id'] ?? null,
                                'survey_question_id' => $surveyQuestion->id,
                                'name' => $value2['name'],
                            ];
                            $surveyQuestionOption = $this->surveyQuestionOptionRepository->store($infoSave);
                        }
                    }
                }
            }

            $msg = 'agregado';
            $action = 'create';
            if (!empty($request['id'])) {
                $msg = 'modificado';
                $action = 'update';
            }
            logInfo($action, 'encuestas', 'Encuesta ' . $data->id . ' ' . $msg . ' correctamente',$dataOld,$data);
            DB::commit();

            return response()->json(['code' => 200, 'message' => 'Registro ' . $msg . ' correctamente', 'data' => $data]);
        } catch (Exception $th) {
            DB::rollBack();

            return response()->json(['code' => 500, 'message' => $th->getMessage(), 'line' => $th->getLine()], 500);
        }
    }

    public function delete($id)
    {
        try {
            DB::beginTransaction();
            $dataOld = $data = $this->surveyRepository->find($id);
            if ($data) {
                $dataNew = $this->surveyRepository->delete($id);
                $msg = 'Registro eliminado correctamente';
                logInfo('delete', 'encuestas', 'Encuesta ' . $data->id . ' eliminado correctamente',$dataOld,$dataNew);
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
            $data = $this->surveyRepository->find($id);
            $data = new SurveyFormResource($data);
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

    public function sendMail(Request $request)
    {
        try {
            $emails = [];
            if ($request->input("typeData") == "correos") {
                $emails = explode(';', $request->input('emails', []));
            }
            if ($request->input("typeData") == "cargos") {
                foreach ($request->input("charges") as $key => $value) {
                    $charge = $this->chargeRepository->find($value, ["users"]);
                    $emails = [...$emails, ...$charge->users->pluck("email")];
                }
            }
            foreach ($emails as $key => $value) {
                $email = trim($value);
                $this->mailService->setEmailTo($email);
                $this->mailService->setView('Mails.SurveyMail');
                $this->mailService->setSubject('Responder encuesta');

                $url = $request->input('serverFront') . 'SurveyAnswer/' . $request->input('survey_id') . '/' . base64_encode($email);
                $this->mailService->sendMessage(['url' => $url]);
            }

            return response()->json(['code' => 200, 'message' => 'Encuesta enviada con éxito']);
        } catch (Exception $th) {
            return response()->json(['code' => 500, 'message' => $th->getMessage()], 500);
        }
    }

    public function getInfoToAnswer($id, $email)
    {
        try {
            $survey = $this->surveyRepository->find($id, ['questions.options', "answers"]);
            if (!$survey->answerSeveralTimes) {
                if ($survey->answers->pluck('email')->contains(base64_decode($email))) {
                    return response()->json(['code' => 422, 'message' => "Usted ya respondio a la encuesta"]);
                }
            }

            foreach ($survey->questions as $key => $value) {
                if ($value->type_question == 3) {
                    $value['answer'] = [];
                } else {
                    $value['answer'] = '';
                }
            }
            return response()->json(['code' => 200, 'survey' => $survey]);
        } catch (Exception $th) {
            return response()->json(['code' => 500, 'message' => $th->getMessage()], 500);
        }
    }

    public function saveAnswer(Request $request)
    {
        try {
            DB::beginTransaction();
            foreach ($request->input('questions') as $key => $value) {
                $dataSave = [
                    'email' => base64_decode($request->input('email')),
                    'survey_id' => $request->input('id'),
                    'survey_question_id' => $value['id'],
                    'answer' => $value['answer'],
                ];
                if ($value['type_question'] == 3) {
                    $dataSave['answer'] = implode(',', $value['answer']);
                }

                $dataNew = $this->surveyAnswersRepository->store($dataSave);
                logInfo('delete', 'Respuesta encuesta', 'Respuesta encuesta ' . $dataNew->id . ' eliminado correctamente',null,$dataNew);

            }
            DB::commit();

            return response()->json(['code' => 200, 'message' => 'Encuesta respondida correctamente']);
        } catch (Exception $th) {
            DB::rollBack();

            return response()->json(['code' => 500, 'message' => $th->getMessage()], 500);
        }
    }

    public function infoReport($id)
    {
        try {
            $survey = $this->surveyRepository->find($id, ['questions', 'answers.question']);
            $dataReport = [];
            $headers = [];

            foreach ($survey->answers as $key => $value) {

                if (!in_array($value['question']['question'], $headers)) {
                    $headers[] = $value['question']['question'];
                }

                if ($value['question']['type_question'] == 2) {
                    $info = $this->surveyQuestionOptionRepository->find($value['answer']);
                    $value['answer'] = $info->name;
                }
                if ($value['question']['type_question'] == 3) {
                    $explode = explode(',', $value['answer']);
                    $array = [];
                    foreach ($explode as $key2 => $value2) {
                        $info = $this->surveyQuestionOptionRepository->find(trim($value2));
                        $array[] = $info->name;
                    }
                    $value['answer'] = $array;
                }

                if (!isset($dataReport[$value['email']])) {
                    $dataReport[$value['email']]['survey_id'] = $survey['id'];
                    $dataReport[$value['email']]['email'] = $value['email'];

                    $dataReport[$value['email']]['pregunta'][] = [
                        'question_id' => $value['id'],
                        'question' => $value['question']['question'],
                        'answer' => $value['answer'],
                    ];
                } else {

                    $dataReport[$value['email']]['pregunta'][] = [
                        'question_id' => $value['id'],
                        'question' => $value['question']['question'],
                        'answer' => $value['answer'],
                    ];
                }
            }
            $d = [];
            foreach ($dataReport as $key => $value) {
                $d[] = $value;
            }

            return response()->json(['code' => 200, 'dataReport' => $d, 'headers' => $headers]);
        } catch (Exception $th) {
            return response()->json(['code' => 500, 'message' => $th->getMessage()], 500);
        }
    }
}
