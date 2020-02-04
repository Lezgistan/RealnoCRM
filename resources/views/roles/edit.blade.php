
@extends('layouts.app')

@section('content')


    <div class="row justify-content-center">
        <div class="col-8 col-lg-4">

            {{ Form::model($role,['url'=>route('roles.update',$role),'method'=>'PATCH']) }}

            @include('roles._form')

            <div class="pt-3 text-center">
                <button class="btn btn-primary">Сохранить</button>
            </div>
            {{ Form::close() }}
        </div>
    </div>

@stop
