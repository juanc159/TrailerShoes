<?php

namespace App\Repositories;

use App\Models\Form;

class FormRepository extends BaseRepository
{
    private $formInputRepository;

    private $formInputOptionRepository;

    public function __construct(Form $modelo, FormInputRepository $formInputRepository, FormInputOptionRepository $formInputOptionRepository)
    {
        parent::__construct($modelo);
        $this->formInputRepository = $formInputRepository;
        $this->formInputOptionRepository = $formInputOptionRepository;
    }

    public function list($request = [], $with = [])
    {
        $data = $this->model->with($with)->where(function ($query) use ($request) {
            if (! empty($request['name'])) {
                $query->where('name', 'like', '%'.$request['name'].'%');
            }
            if (! empty($request['null_requirement_type_id'])) {
                $query->whereNull('requirement_type_id');
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

    public function store($request, $arrayInputs)
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

        if ($arrayInputs && count($arrayInputs) > 0) {
            foreach ($arrayInputs as $key => $value) {
                // return $value;
                if (isset($value['delete']) && $value['delete'] == 'delete') {
                    $formInput = $this->formInputRepository->find($value['id']);
                    $formInput->options()->delete();
                    $formInput->delete();
                } else {
                    $infoSave = [
                        'id' => $value['id'] ?? null,
                        'form_id' => $data->id,
                        'label' => $value['label'],
                        'type_input' => $value['type_input'],
                        'required' => isset($value['required']) ? $value['required'] : 0,
                    ];
                    // return $infoSave;
                    $arrayOptions = $value['arrayOptions'];
                    $formInput = $this->formInputRepository->store($infoSave);
                    if ($formInput->type_input != 1) {
                        foreach ($arrayOptions as $key2 => $value2) {
                            if (isset($value2['delete']) && $value2['delete'] == 'delete') {
                                $formInputOption = $this->formInputOptionRepository->store($infoSave);
                                $formInputOption->delete();
                            } else {
                                $infoSave = [
                                    'id' => $value2['id'] ?? null,
                                    'form_input_id' => $formInput->id,
                                    'name' => $value2['name'],
                                ];
                                $this->formInputOptionRepository->store($infoSave);
                            }
                        }
                    }
                }
            }
        }

        return $data;
    }

    public function listSelect($request = [])
    {
        return $this->model->where(function ($query) use ($request) {
            if (isset($request['requirement_type_id_null']) && $request['requirement_type_id_null'] == true) {
                $query->whereNull('requirement_type_id');
            }
            if (isset($request['requirement_type_id_null']) && $request['requirement_type_id_null'] == false) {
                $query->whereNotNull('requirement_type_id');
            }
            if (isset($request['internal']) && $request['internal'] == true) {
                $query->whereNotNull('requirement_type_id');
            }
        })
            ->where(function ($query) use ($request) {
                if (isset($request['internal']) && $request['internal'] == true) {
                    $query->orWhereHas('requirement_type', function ($x) {
                        $x->where('internal', 1);
                    });
                }
                if (isset($request['external']) && $request['external'] == true) {
                    $query->orWhereHas('requirement_type', function ($x) {
                        $x->where('external', 1);
                    });
                }
            })
            ->get()->map(function ($value) {
                return [
                    'value' => $value->id,
                    'title' => $value->name,
                    'internal' => $value->requirement_type?->internal,
                    'external' => $value->requirement_type?->external,
                    'requirement_type_id' => $value->requirement_type_id,
                    'arrayInputs' => $value->inputs->map(function ($value) {
                        return [
                            'id' => $value->id,
                            'label' => $value->label,
                            'type_input' => $value->type_input,
                            'answer' => '',
                            'required' => $value->required,
                            'arrayOptions' => $value->options->map(function ($op) {
                                return [
                                    'value' => $op->id,
                                    'title' => $op->name,
                                ];
                            }),
                        ];
                    }),
                ];
            });
    }
}
