<?php

use App\Models\Users\UserDoc;

/**
 * @var UserDoc $documents
 */
?>
@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row pb-3">
            <div class="col">
                {{ Form::open(['url'=>route('documents.index'),'method'=>'get', 'autocomplete'=>'off','class'=>'form-inline']) }}
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
                <a href="{{route('documents.create')}}" class="btn btn-outline-success">
                    Добавить <i class="fas fa-plus"></i>
                </a>
            </div>
        </div>
        @foreach($documents as $document)
            <div class="row border-bottom table-item pb-1 mb-1">
                <div class="col-1">
                    {{ $document->getId()}}
                </div>
                <div class="col-3 pl-0">
                    <a class="text-dark stretched-link"
                       href="{{route('users.show',$document->user()->get()->first())}}">
                        {{$document->user()->get()->first()->getName()}}</a><br>
                    <small class="text-muted">Автор</small>
                </div>
                <div class="col-4">
                    <a class="text-dark stretched-link" href="{{route('documents.show',$document)}}">
                        {{ $document->getName()}}</a><br>
                    <small class="text-muted">Название документа</small>
                </div>
                <div class="col-2">
                    {{$document->versions()->get()->last()->getUpdatedAt()->diffForHumans()}}<br>
                    <small class="text-muted">Последнее обновления</small>
                </div>
                <div class="col-2 m-auto">
                    <div class="btn-group table-links">
                        <a href="{{ route('document.versions',$document) }}" class="btn btn-outline-secondary">
                            <i class="fas fa-list"></i>
                        </a>
                    </div>
                    <div class="btn-group table-links">
                        <a href="{{ route('documents.edit',$document) }}" class="btn btn-outline-secondary">
                            <i class="fas fa-plus"></i>
                        </a>
                    </div>
                        {{ Form::open(['url'=>route('documents.destroy',$document),'method'=>'DELETE','class'=>'btn-group']) }}
                        <button class="btn btn-outline-danger table-links"
                                onclick="return confirm('Удалить документ №{{ $document->getId() }} и все его версии?')">
                            <i class="fa fa-fw fa-trash"></i>
                        </button>
                        {{ Form::close() }}
                </div>
            </div>
        @endforeach
    </div>
    @include('form._pagination',[
        'elements'=>$documents,
    ])


@stop
