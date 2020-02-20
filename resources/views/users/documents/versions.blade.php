<?php
/**
 * @var \App\Models\Users\DocumentVersion $version
 */
?>

@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row pb-3">
            <div class="col">
                {{ Form::open(['url'=>route('users.index'),'method'=>'get', 'autocomplete'=>'off','class'=>'form-inline']) }}
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
                <a href="{{ route('users.create') }}" class="btn btn-outline-success">
                    <i class="fas fa-user-plus"></i> Добавить
                </a>
            </div>
        </div>

        @foreach($versions as $version)
            <div class="row border-bottom py-1 table-item">
                <div class="col-1">
                    {{ $version->getKey()}}
                </div>
                <div class="col-7">
                        {{ $version->getVersion() }}
                </div>

                <div class="col-4">
                    {{$version->getUserId()}}
                </div>
            </div>
        @endforeach
    </div>
    @include('form._pagination',[
        'elements'=>$versions,
    ])


@stop
