<?php
/**
 * @var \App\Models\Users\UserDoc $document
 */
?>

@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row pb-3">
            <div class="col">
                {{ Form::open(['url'=>route('document.versions',$document),'method'=>'get', 'autocomplete'=>'off','class'=>'form-inline']) }}
                <div class="input-group">
                    @include('form._input',[
        'name'=>'search',
        'placeholder'=>'Поиск'
    ])
                    <div class="input-group-append">
                        <button type="submit" class="btn btn-outline-secondary">
                            <i class="fas fa-fw fa-search"></i>
                        </button>
                    </div>
                </div>
                {{Form::close()}}
            </div>
            <div class="col-auto">
                <a href="{{route('documents.edit',$document)}}" class="btn btn-outline-success">
                    Добавить <i class="fas fa-plus"></i>
                </a>
            </div>
        </div>

        @foreach($versions as $version)
            <div class="row border-bottom py-1 table-item">
                <div class="col-1">
                    {{ $version->getKey()}}
                </div>
                <div class="col-3">
                    <a class="text-dark stretched-link"
                       href="{{route('users.show',$version->getUser())}}">
                        {{ $version->getUser()->getName()}}</a><br>
                    <small class="text-muted">Автор</small>
                </div>
                <div class="col-4">
                  {{$version->getUpdatedAt()->format('Y-m-d H:i')}}<br>
                    <small class="text-muted">Дата загрузки</small>
                </div>
                <div class="col-1">
                    {{$version->getSizeFormatted()}}<br>
                    <small class="text-muted">Размер</small>
                </div>

                <div class="col-3 m-auto table-links text-lg-right">
                    <a href="{{$version->getPublicPath()}}" class="btn btn-outline-secondary" target="_blank">
                        <i class="fas fa-file-download"></i>
                    </a>
                </div>
            </div>
        @endforeach
    </div>
    @include('form._pagination',[
        'elements'=>$versions,
    ])


@stop
