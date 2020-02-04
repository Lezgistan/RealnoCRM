@extends('layouts.app')

@section('content')

    <div class="div">
        <div class="div">
            {{Form::model($permission,['url'=>route('permissions.update',$permission),'method'=>'PATCH']) }}

            @include('permissions._form')

            <div class="div">
                <button class="bth bth-primary">Сохранить</button>
            </div>
            {{Form::close()}}
        </div>
    </div>
@stop
