<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Model;

class BaseRepository
{
    protected $model;

    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    public function new($data = [])
    {
        return new $this->model($data);
    }

    public function all()
    {
        return $this->model->all();
    }

    public function first()
    {
        return $this->model->first();
    }

    public function get($with = [])
    {
        return $this->model->with($with)->get();
    }

    public function find($id, $with = [], $select = '*', $withCount = [])
    {
        return $this->model->select($select)->withCount($withCount)->with($with)->find($id);
    }

    public function save(Model $model)
    {
        $model->save();

        return $model;
    }

    public function make(Model $model) //esto es para simular un registro
    {
        $model->make();

        return $model;
    }

    public function delete($id)
    {
        $model = $this->model->find($id);
        $model->delete();

        return $model;
    }

    public function changeState($id, $estado, $column = 'estado', $with = [])
    {
        $model = $this->model->with($with)->find($id);
        $model[$column] = $estado;
        $model->save();

        return $model;
    }

    public function pdf($view, $data = [], $file_name = 'archivo', $directory = 'pdfTemporal')
    {
        $file_path = storage_path('app/public/'.$directory.'/'.$file_name.'.pdf');
        if (! file_exists(storage_path('app/public/'.$directory))) {
            mkdir(storage_path('app/public/'.$directory));
        }
        $pdf = \PDF::loadView($view, $data);

        $pdf->save($file_path);

        return 'storage/'.$directory.'/'.$file_name.'.pdf';
    }
}
