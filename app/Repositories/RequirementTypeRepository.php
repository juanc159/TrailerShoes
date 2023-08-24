<?php

namespace App\Repositories;

use App\Models\RequirementType;

class RequirementTypeRepository extends BaseRepository
{
    public function __construct(RequirementType $modelo)
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
            if (! empty($request['internal'])) {
                $query->where('internal', $request['internal']);
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
        $arrayCharges = $request['arrayCharges'];
        unset($request['arrayCharges']);

        if (! empty($request['id'])) {
            $data = $this->model->find($request['id']);
        } else {
            $data = $this->model::newModelInstance();
        }

        foreach ($request as $key => $value) {
            $data[$key] = $request[$key];
        }
        $data->save();

        $info = [];
        foreach ($arrayCharges as $key => $value) {
            $info[$value['charge_id']]['order'] = $value['order'];
        }
        $data->charges()->sync($info);

        return $data;
    }
}
