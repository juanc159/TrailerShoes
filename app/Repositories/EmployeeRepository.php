<?php

namespace App\Repositories;

use App\Models\Employee;

class EmployeeRepository extends BaseRepository
{
    public function __construct(Employee $modelo)
    {
        parent::__construct($modelo);
    }

    public function list($request = [], $with = [], $select = ['*'])
    {
        $data = $this->model->select($select)->with($with)->where(function ($query) use ($request) {
            if (! empty($request['name'])) {
                $query->where('name', 'like', '%'.$request['name'].'%');
            }
            if (! empty($request['state'])) {
                $query->where('state', $request['state']);
            }
        })
            ->where(function ($query) use ($request) {
                if (! empty($request['searchQuery'])) {
                    $query->orWhere('name', 'like', '%'.$request['searchQuery'].'%');
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

    public function selectList($request = [])
    {
        $data = $this->model->get()->map(function ($value) {
            return  [
                "value" => $value->id,
                "title" => $value->name,
            ];
        });

        return $data;
    }
}
