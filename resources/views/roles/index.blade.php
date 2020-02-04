<?php

use App\Models\Users\Role;

/**
 * @var Role $role
 */
?>
@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row pb-3">
            <div class="col">
                {{ Form::open(['url'=>route('roles.index'),'method'=>'get', 'autocomplete'=>'off','class'=>'form-inline']) }}
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
                <a href="{{route('roles.create')}}" class="btn btn-outline-success">
                    Добавить <i class="fas fa-plus"></i>
                </a>
            </div>
        </div>


        @foreach($roles as $role)
            <div class="row border-bottom table-item pb-1 mb-1">
                <div class="col-1">
                    {{ $role->getKey() }}
                </div>
                <div class="col-8">
                    <div>
                        {{ $role->getDisplayName() }}
                    </div>
                    <div class="text-secondary">
                        {{ $role->getName() }}
                    </div>
                </div>

                <div class="col-3 table-links row d-flex justify-content-end text-right">
                    <div class="btn-group table-links">
                        <a href="{{ route('roles.edit',$role) }}" class="btn btn-outline-secondary">
                            <i class="fa fa-fw fa-edit fa-fw"></i>
                        </a>
                    </div>
                    {{ Form::open(['url'=>route('roles.destroy',$role),'method'=>'DELETE','class'=>'btn-group']) }}
                    <button class="btn btn-outline-danger table-links"
                            onclick="return confirm('Удалить роль №{{ $role->getKey() }}?')">
                        <i class="fa fa-fw fa-trash"></i>
                    </button>
                    {{ Form::close() }}
                </div>
            </div>
        @endforeach
    </div>
    @include('form._pagination',[
        'elements'=>$roles,
    ])


@stop
