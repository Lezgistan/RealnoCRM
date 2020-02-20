<?php
/**
 * @var \App\Models\Users\UserDoc $doc
 */
?>
@extends('layouts.app')

@section('content')
    <div class="container d-flex justify-content-center w-100">
        <div class="w-25">
            {{ Form::open(['url'=>route('document.versions.store',$document),'files'=>true,'method'=>'POST']) }}

            @include('form._file',[
          'name'=>'document_version',
           'type'=>'file',
           'required'=>true,
           'label'=>'Выбрать файл',
      ])
            <button class="btn btn-primary w-100 mt-3">Создать</button>
            {{ Form::close() }}
        </div>
    </div>
@stop
