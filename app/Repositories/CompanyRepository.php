<?php

namespace App\Repositories;

use App\Models\Company;

class CompanyRepository extends BaseRepository{

  public function __construct(Company $modelo){
    parent::__construct($modelo);
  }

  public function list($request = [], $with = [], $select = ['*']){
    $data = $this->model->select($select)->with($with)->where(function ($query) use ($request) {
      if( !empty($request['name']) ){
        $query->where('name', 'like', '%'.$request['name'].'%');
      }
    })->where(function ($query) use ($request) {
      if( !empty($request['searchQuery']) ){
        $query->orWhere('name', 'like', '%'.$request['searchQuery'].'%');
      }
    })->orderBy($request['sort_field'] ?? 'id', $request['sort_direction'] ?? 'asc');

    if( empty($request['typeData']) ){
      $data = $data->paginate($request['perPage'] ?? 10);
    }else{
      $data = $data->get();
    }
    return $data;
  }

  public function store($request){

    if( $request['id'] != 'null' ){
      $data = $this->model->find($request['id']);
    }else{
      $request['id'] = NULL;
      $data = $this->model::newModelInstance();
    }

    $imageIcon = $request->file('image_icon');
    $imageLogo = $request->file('image_logo');
    $imageCover = $request->file('image_cover');

    if( $imageIcon ){
      $path = $request->root() . '/storage/'.
      $imageIcon->storeAs('company', 'image_icon.'.$imageIcon->getClientOriginalExtension(), 'public');
      $data->image_icon = $path;
    }
    if( $imageLogo ){
      $path = $request->root() . '/storage/'.
      $imageLogo->storeAs('company', 'image_logo.'.$imageLogo->getClientOriginalExtension(), 'public');
      $data->image_logo = $path;
    }
    if( $imageCover ){
      $path = $request->root() . '/storage/'.
      $imageCover->storeAs('company', 'image_cover.'.$imageCover->getClientOriginalExtension(), 'public');
      $data->image_cover = $path;
    }

    $request = $request->all();
    unset( $request['image_icon'], $request['image_logo'], $request['image_cover'] );
    foreach( $request as $key => $value ){
      $data[$key] = $request[$key];
    }
    $data->save();
    return $data;
  }

}
