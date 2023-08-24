<?php

namespace App\Repositories;

use App\Models\Event;

class EventRepository extends BaseRepository
{
    public function __construct(Event $modelo)
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
            if (isset($request['public'])) {
                $query->where('public', $request['public']);
            }
            if (! empty($request['user_id'])) {
                $query->where('user_id', $request['user_id']);
                $query->orWhereHas('users', function ($x) use ($request) {
                    $x->where('user_id', $request['user_id']);
                });
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

    public function eventGoogle($event_google_id)
    {
        $data = $this->model->where('event_google_id', $event_google_id)->first();

        return $data;
    }

}
