<?php
use App\Models\Users\UserDoc;
use App\Models\Users\User;
/**
 * @var UserDoc $documents
 */
?>
@extends('layouts.app')

@section('content')
    <div class="row pb-3">
        <div class="col">
            {{ Form::open(['url'=>route('users.documents',$documents),'method'=>'get','class'=>'form-inline']) }}
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
            {{ Form::close() }}
        </div>
    </div>
    @forelse($documents as $document)
        <div class="row border-bottom py-2 table-item">
            <div class="col-1">
                {{ $document->getKey() }}
            </div>
            <div class="col-3">
                <a class="text-dark stretched-link" href="{{route('documents.show',$document)}}">
                    {{ $document->getName()}}</a><br>
                <small class="text-muted">Название документа</small>
            </div>
            <div class="col-2">
                {{ $document->getCreatedAt()->format('Y.m.d H:i') }}
                <br>
                <small class="text-muted">Дата создания</small>
            </div>
            <div class="col-3">
                {{$document->versions()->get()->last()->getUpdatedAt()->diffForHumans()}}<br>
                <small class="text-muted">Последнее обновления</small>
            </div>
            <div class="col-3 m-auto text-lg-right">
                <div class="btn-group table-links">
                    <a href="{{ route('document.versions',$document) }}" class="btn btn-outline-secondary">
                        <i class="fas fa-list"></i>
                    </a>
                </div>
                <div class="btn-group table-links">
                    <a href="{{$document->versions()->get()->last()->getPublicPath()}}" class="btn btn-outline-secondary" target="_blank">
                        <i class="fas fa-file-download"></i>
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
    @empty
        <div class="alert alert-info">
            Документы отсутствуют
        </div>
    @endforelse
    @include('form._pagination',[
                'elements'=>$documents,
            ])
@stop
