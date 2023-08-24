<?php

namespace App\Repositories;

use App\Models\DynamicPage;

class DynamicPageRepository extends BaseRepository
{
    public function __construct(DynamicPage $modelo)
    {
        parent::__construct($modelo);
    }

    public function list($request = [], $with = [])
    {
        $data = $this->model->with($with)->where(function ($query) use ($request) {
            if (! empty($request['name'])) {
                $query->where('name', $request['name']);
            }
            if (! empty($request['route'])) {
                $query->where('route', $request['route']);
            }

        })
            ->where(function ($query) use ($request) {
                if (! empty($request['searchQuery'])) {
                    $query->orWhere('name', 'like', '%'.$request['searchQuery'].'%');
                }
            });

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
            $data = $this->model;
        }

        foreach ($request->all() as $key => $value) {
            $data[$key] = $request[$key];
        }

        $data->save();

        return $data;
    }
}
