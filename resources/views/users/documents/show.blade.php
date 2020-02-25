<?php
/**
 * @var \App\Models\Users\UserDoc $document
 */
?>
@extends('layouts.app')

@section('content')

    <div class="row">
        <div class="ml-2 btn">
            <a href="{{ route('document.versions',$document) }}" title="Список версий документа">
                <i class="fas fa-list"></i>
            </a>
        </div>
        <div class="ml-1 btn">
            @if (null !== $document->versions()->orderByDesc('id')->first())
                <a href="{{$document->versions()->orderByDesc('id')->first()->getPublicPath() }}" title="Скачать документ"
                   target="_blank">
                    <i class="fas fa-file-download"></i>
                </a>
            @endif
        </div>
        <div class="ml-1 btn">
            <a href="{{ route('documents.edit',$document) }}" title="Добавить новую версию">
                <i class="fas fa-plus"></i>
            </a>
        </div>
        <div class="ml-1">
            {{ Form::open(['url'=>route('documents.destroy',$document),'method'=>'DELETE','class'=>'btn-group']) }}
            <button class="btn"
                onclick="return confirm('Удалить документ №{{ $document->getKey() }} и все его версии?')">
                <i class="fa fa-fw fa-trash"></i>
            </button>
            {{ Form::close() }}
        </div>
    </div>
    <div class="embed-responsive embed-responsive-16by9">
        <iframe
            src='https://view.officeapps.live.com/op/embed.aspx?src={{$document->versions()->orderByDesc('id')->first()->getPublicPath()}}'></iframe>
    </div>



@stop

