<?php

use App\Models\Users\permission;

/**
 * @var permission $permission
 */
?>
@extends('layouts.app')

@section('content')

<div class="container">
    <div class="row pb-3">
        <div class="col">
            {{ Form::open(['url'=>route('permissions.index'),'method'=>'get', 'autocomplete'=>'off','class'=>'form-inline']) }}
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
                <a href="{{ route('permissions.create') }}" class="btn btn-outline-success">
                    Добавить <i class="fas fa-plus"></i>
                </a>
            </div>

        </div>

    @foreach($permissions as $permission)
        <div class="row border-bottom">
            <div class="col-1">
                {{ $permission->getId() }}
            </div>

            <div class="col-2">
                {{ $permission->getName() }}
            </div>

            <div class="col-2">
                {{ $permission->getDisplayName()  }}
            </div>
            <div class="col-4">
                {{ $permission->getDescription()  }}
            </div>
            <div class="col-2">
                <a href="{{route('permissions.edit',$permission)}}">
                    <button class="btn btn-primary">Редактировать</button>
                </a>
            </div>
            <div class="col-1">
                {{Form::model($permission,['url'=>route('permissions.destroy',$permission),'method'=>'DELETE']) }}
                <button class="btn btn-danger"
                        onclick=" return confirm('Вы действительно хотите удалить эту роль?')">Удалить
                </button>
                {{Form::close()}}
            </div>
            <div class="col-1">

            </div>

        </div>
</div>
</div>
    @endforeach



@stop
