@extends('layouts.app')

@section('content')

    <div class="row justify-content-center">
        <div class="col-8 col-lg-4">
            {{ Form::model($user,['url'=>route('users.update',$user),'method'=>'PATCH','enctype'=>"multipart/form-data",]) }}
            @include('users._form')
            <div class="pt-3 text-center">
                <button class="btn btn-primary">Сохранить</button>
            </div>
            {{ Form::close() }}
        </div>
    </div>

@stop
