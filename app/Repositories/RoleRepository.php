<?php

namespace App\Repositories;

use App\Models\RoleNew;

class RoleRepository extends BaseRepository
{
    public function __construct(RoleNew $modelo)
    {
        parent::__construct($modelo);
    }

    public function list($request = [], $with = [])
    {
        $data = $this->model->with($with)->where(function ($query) use ($request) {
            if (! empty($request['name'])) {
                $query->where('name', 'like', '%'.$request['name'].'%');
            }
            if (! empty($request['state'])) {
                $query->where('state', $request['state']);
            }
            if (! empty($request['description'])) {
                $query->where('description', $request['description']);
            }
        })
            ->where(function ($query) use ($request) {
                if (! empty($request['searchQuery'])) {
                    $query->orWhere('name', 'like', '%'.$request['searchQuery'].'%');
                    $query->orWhere('guard_name', 'like', '%'.$request['searchQuery'].'%');
                    $query->orWhere('description', 'like', '%'.$request['searchQuery'].'%');
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
            $data = $this->model;
        }

        $permissions = $request['permissions'] ?? [];
        unset($request['permissions']);
        foreach ($request->all() as $key => $value) {
            $data[$key] = $request[$key];
            $data['name'] = $request->name;
        }
        $data->save();

        if (count($permissions) > 0) {
            $data->syncPermissions($permissions);
        }

        return $data;
    }
}
