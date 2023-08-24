<?php

use App\Models\LogInfo;
use Illuminate\Support\Facades\Auth;

function logInfo($action, $module, $description,$before= null,$after= null)
{

    $data = new LogInfo();
    $data->action = $action;
    $data->module = $module;
    $data->description = $description;
    $data->before = $before;
    $data->after = $after;
    $data->user_id = Auth::user()->id;
    $data->save();

    return $data;
}
