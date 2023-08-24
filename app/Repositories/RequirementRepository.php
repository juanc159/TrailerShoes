<?php

namespace App\Repositories;

use App\Models\Requirement;

class RequirementRepository extends BaseRepository
{
    public function __construct(Requirement $modelo)
    {
        parent::__construct($modelo);
    }

    public function list($request = [], $with = [])
    {
        $data = $this->model->with($with)->where(function ($query) use ($request) {
            if (! empty($request['user_id'])) {
                $query->where('user_id', $request['user_id']);
            }
            if (! empty($request['state_id'])) {
                $query->where('state_id', $request['state_id']);
            }
            if (! empty($request['charge_id'])) {
                $query->where('charge_id', $request['charge_id']);
            }
            if (! empty($request['requirement_type_id'])) {
                $query->where('requirement_type_id', $request['requirement_type_id']);
            }
            if (! empty($request['requirement_type_ids'])) {
                $query->whereIn('requirement_type_id', $request['requirement_type_ids']);
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
}
