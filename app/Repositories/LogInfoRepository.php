<?php

namespace App\Repositories;

use App\Models\LogInfo;

class LogInfoRepository extends BaseRepository
{
    public function __construct(LogInfo $modelo)
    {
        parent::__construct($modelo);
    }

    public function list($request = [], $with = [])
    {
        $data = $this->model->with($with)->where(function ($query) use ($request) {
            if (! empty($request['action'])) {
                $query->where('action', 'like', '%'.$request['action'].'%');
            }
            if (! empty($request['module'])) {
                $query->where('module', 'like', '%'.$request['module'].'%');
            }
            if (! empty($request['description'])) {
                $query->where('description', 'like', '%'.$request['description'].'%');
            }
            if (! empty($request['dateIntial'])) {
                $query->whereDate('created_at', '>=', $request['dateIntial']);
            }
            if (! empty($request['dateFinal'])) {
                $query->whereDate('created_at', '<=', $request['dateFinal']);
            }
            if (! empty($request['user_name'])) {
                $query->whereHas('user', function ($x) use ($request) {
                    $x->where('name', 'like', '%'.$request['user_name'].'%');
                    $x->orWhere('lastName', 'like', '%'.$request['user_name'].'%');
                });
            }

        })
            ->where(function ($query) use ($request) {
                if (! empty($request['searchQuery'])) {
                    $query->orWhere('action', 'like', '%'.$request['searchQuery'].'%');
                    $query->orWhere('module', 'like', '%'.$request['searchQuery'].'%');
                    $query->orWhere('description', 'like', '%'.$request['searchQuery'].'%');
                    $query->orWhereHas('user', function ($x) use ($request) {
                        $x->where('name', 'like', '%'.$request['searchQuery'].'%');
                        $x->orWhere('lastName', 'like', '%'.$request['searchQuery'].'%');
                    });
                }
            })
            ->orderBy($request['sort_field'] ?? 'id', $request['sort_direction'] ?? 'asc');

        if (empty($request['typeData'])) {
            $data = $data->paginate($request['perPage'] ?? 10);
        } else {
            $data = $data->get();
        }

        return $data;
    }

    public function store($request)
    {
        if (! empty($request['id'])) {
            $data = $this->model->find($request['id']);
        } else {
            $data = $this->model::newModelInstance();
        }

        foreach ($request as $key => $value) {
            $data[$key] = $request[$key];
        }
        $data->save();

        return $data;
    }
}
