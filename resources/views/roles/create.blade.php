@extends('layouts.app')

@section('content')<div class="container d-flex justify-content-center w-100">
    <div class="w-25">
        {{ Form::open(['url'=>route('roles.store')]) }}

        @include('form._input',[
            'name'=>'name',
            'required'=>true,
            'label'=>'Имя'
        ])

        @include('form._input',[
        'name'=>'display_name',
          'required'=>true,
        'label'=>'Отображаемое имя'
    ])

        @include('form._input',[
            'name'=>'description',
            'label'=>'Краткое описание'
        ])
        <button class="btn btn-primary w-100 mt-3">Создать</button>
        {{ Form::close() }}
    </div>
</div>
@stop
