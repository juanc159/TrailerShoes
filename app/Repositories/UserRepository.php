<?php

namespace App\Repositories;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserRepository extends BaseRepository
{
    public function __construct(User $modelo)
    {
        parent::__construct($modelo);
    }

    public function list($request = [], $with = [], $select = ['*'])
    {
        $data = $this->model->select($select)
            ->with($with)
            ->where(function ($query) use ($request) {
                if (! empty($request['name'])) {
                    $query->where('name', 'like', '%'.$request['name'].'%');
                }
                if (isset($request['state'])) {
                    $query->where('state', $request['state']);
                }
                if (isset($request['role_id'])) {
                    $query->where('role_id', $request['role_id']);
                }
                if (isset($request['identification'])) {
                    $query->where('identification', $request['identification']);
                }
                if (isset($request['charge_id'])) {
                    $query->where('charge_id', $request['charge_id']);
                }
            })
            ->where(function ($query) use ($request) {
                if (! empty($request['searchQuery'])) {
                    $query->orWhere('name', 'like', '%'.$request['searchQuery'].'%');
                    $query->orWhere('email', 'like', '%'.$request['searchQuery'].'%');
                    // $query->orWhereHas("position", function ($x) use ($request) {
                    //     $x->where("name", "like", "%" . $request["searchQuery"] . "%");
                    // });
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
        foreach ($request as $key => $value) {
            $request[$key] = $request[$key] != 'null' ? $request[$key] : null;
        }
        if (! empty($request['id'])) {
            $data = $this->model->find($request['id']);
        } else {
            $data = $this->model::newModelInstance();
        }

        foreach ($request as $key => $value) {
            $data[$key] = $request[$key];
        }

        if( !empty($data['password']) ){
            $data['password'] = Hash::make($data['password']);
        }
        else{
            unset($data['password']);
        }

        $data->save();

        if (! empty($data['role_id'])) {
            $data->roles()->sync($data['role_id']);
        }

        return $data;
    }

    public function register($request)
    {
        $data = $this->model;

        foreach ($request as $key => $value) {
            $data[$key] = $request[$key];
        }
        $data['password'] = Hash::make($data['password']);
        $data->save();
        $data->roles()->sync($data['role_id']);

        return $data;
    }

    public function listSelect()
    {
        return $this->model->get()->map(function ($value) {
            return [
                'value' => $value->id,
                'title' => $value->name,
                'email' => $value->email,
            ];
        });
    }
}
