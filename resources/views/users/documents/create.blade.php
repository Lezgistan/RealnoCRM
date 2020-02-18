@extends('layouts.app')

@section('content')
    <div class="container d-flex justify-content-center w-100">
        <div class="w-25">
            {{ Form::open(['url'=>route('documents.store'),'files'=>true,'method'=>'POST',]) }}
            @include('form._input',[
                'name'=>'name',
                'required'=>true,
                'label'=>'Название документа'
            ])
            @include('form._input',[
                'name'=>'version',
                'label'=>'Версия документа'
                        ])
            @include('form._file',[
          'name'=>'document',
           'type'=>'file',
           'required'=>true,
           'label'=>'Выбрать файл',
      ])
            <button class="btn btn-primary w-100 mt-3">Создать</button>
            {{ Form::close() }}
        </div>
    </div>
@stop
