@extends('layouts.app')

@section('content')
    <?php
    /**
     * @var \App\Models\Users\User $user
     * @var \App\Models\Users\Role $role
     */
    ?>
    @if (isset($user))
        <div class="row">
            <div class="col-4 col-lg-2 mx-auto d-block text-center text-lg-right">
                <img src="{{$user->getAvatarUrl()}}" alt="Аватарка" class="rounded-circle img-fluid mb-3 w-75">
            </div>
            <div class="col-12 col-lg-10 text-center text-lg-left">
                <h3 class="m-0">{{$user->getFirstName()}} {{$user->getMiddleName()}} {{$user->getLastName()}}
                </h3>

                @if($user->isOnline())
                    <small class="text-muted">В сети</small>
                @elseif($user->getLastActive()!=null)
                    <small class="text-muted">{{ $user->getLastActive()->diffForHumans() }}</small>
                @endif

                <div class="col-12 p-0 col-lg-6">
                    @foreach($roles as $role)
                        @if($user->hasRole($role->getName()))
                            <span class="badge badge-{{$role->getColor()}}">{{ $role->getDisplayName() }}</span>
                        @endif
                    @endforeach
                </div>
                <div class="mt-5">
                    <div>
                        <h4>Личная информация</h4>
                        <small class="text-muted">Email</small>
                        <p>{{$user->getEmail()}}</p>
                    </div>

                </div>
            </div>
        </div>
    @else
    @endif




@stop
