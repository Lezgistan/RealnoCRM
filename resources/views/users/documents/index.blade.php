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
                <div class="col-1">
                    {{$document->getUserId()}}
                </div>
                <div class="col-8">
                        {{ $document->getName() }}
                </div>
            </div>
        @endforeach
    </div>
    @include('form._pagination',[
        'elements'=>$documents,
    ])


@stop
