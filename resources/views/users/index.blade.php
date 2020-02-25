<?php

use App\Models\Users\User;

/**
 * @var User $user
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

        @foreach($users as $user)
            <div class="row border-bottom py-1 table-item">
                <div class="col-1">
                    {{ $user->getKey() }}
                </div>
                <div class="col-1">

                        <img class="img-fluid rounded-circle w-75" src="{{$user->getAvatarUrl()}}"
                             alt="">

                </div>
                <div class="col-6">
                    <a class="text-dark stretched-link" href="{{route('users.show',$user->getKey())}}">
                        {{ $user->getLastName() }}
                        {{ $user->getFirstName() }}
                        {{ $user->getMiddleName() }},
                        {{ $user->getEmail() }}
                    </a>
                    <br>
                    <small class="text-muted">
                        Роли: {{ implode(',',$user->getRoles()) }}
                    </small>


                </div>

                <div class="col-4 text-lg-right m-auto">

                    @if ((0 < $user->logs()->count())|(0 < $user->logsForMe()->count()))
                        <div class="btn-group table-links">
                            <a href="{{ route('users.logs',$user) }}" class="btn btn-outline-secondary">
                                <i class="fa fa-fw fa-archive"></i>
                            </a>
                        </div>
                    @endif


                    <div class="btn-group table-links">
                        <a href="{{ route('users.roles',$user) }}" class="btn btn-outline-secondary">
                            <i class="fa fa-fw fa-user-secret"></i>
                        </a>
                    </div>


                    <div class="btn-group table-links">
                        <a href="{{ route('users.documents',$user) }}" class="btn btn-outline-secondary">
                            <i class="fas fa-file-alt"></i>
                        </a>
                    </div>
                        <div class="btn-group table-links">
                            <a href="{{ route('users.password',$user) }}" class="btn btn-outline-secondary">
                                <i class="fa fa-fw fa-key"></i>
                            </a>
                        </div>

                    <div class="btn-group table-links">
                        <a href="{{ route('users.edit',$user) }}" class="btn btn-outline-secondary">
                            <i class="fa fa-fw fa-edit fa-fw"></i>
                        </a>
                    </div>

                    @if (auth()->id() !== $user->getKey())
                        {{ Form::open(['url'=>route('users.destroy',$user),'method'=>'DELETE','class'=>'btn-group']) }}
                        <button class="btn btn-outline-danger table-links"
                                onclick="return confirm('Удалить пользователя №{{ $user->getKey() }}?')">
                            <i class="fa fa-fw fa-trash"></i>
                        </button>
                        {{ Form::close() }}
                    @else
                        <button class="btn btn-outline-danger d-none"
                                data-toggle="tooltip"
                                data-placement="bottom"
                                title="Вы не можете удалить себя"
                        >
                            <i class="fa fa-fw fa-trash"></i>
                        </button>
                    @endif
                </div>
            </div>
        @endforeach
    </div>
    @include('form._pagination',[
        'elements'=>$users,
    ])


@stop
