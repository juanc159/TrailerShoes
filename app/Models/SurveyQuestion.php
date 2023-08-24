<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SurveyQuestion extends Model
{
    use HasFactory,SoftDeletes;

    public function options()
    {
        return $this->hasMany(SurveyQuestionOption::class, 'survey_question_id', 'id');
    }
}
