@extends('layouts.app')

@section('content')


    {{ Form::open(['url'=>route('permissions.store')]) }}

    @include('form._input',[
        'name'=>'name',
        'required'=>true,
        'label'=>'Системное имя'
    ])

    @include('form._input',[
    'name'=>'display_name',
      'required'=>true,
    'label'=>'Название'
])

    @include('form._input',[
        'name'=>'description',
        'label'=>'Описание'
    ])



    <button class="btn btn-primary">Создать</button>
    {{ Form::close() }}
@stop
