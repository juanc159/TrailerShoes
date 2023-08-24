<?php

namespace App\Http\Resources\Survey;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SurveyFormResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'startDate' => $this->startDate,
            'endDate' => $this->endDate,
            'answerSeveralTimes' => $this->answerSeveralTimes,
            'arrayQuestions' => $this->questions->map(function ($value) {
                return [
                    'id' => $value->id,
                    'question' => $value->question,
                    'type_question' => $value->type_question,
                    'arrayOptions' => $value->options->map(function ($value2) {
                        return [
                            'id' => $value2->id,
                            'name' => $value2->name,
                        ];
                    }),
                ];
            }),
        ];
    }
}
