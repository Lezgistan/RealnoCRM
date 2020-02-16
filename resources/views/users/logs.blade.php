<?php
use App\Models\Users\UserLog;
use App\Models\Users\User;
/**
 * @var UserLog $log
 */
?>
@extends('layouts.app')

@section('content')


    <div class="row pb-3">
        <div class="col">
            {{ Form::open(['url'=>route('users.logs',$user),'method'=>'get','class'=>'form-inline']) }}
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

    @forelse($logs as $log)
    <div class="row border-bottom py-2">
      <div class="col-1">
          №{{ $log->getKey() }}
      </div>
        <div class="col-2">
            <span title="{{$log->created_at}}">
                            {{ $log->created_at->diffForHumans() }}
            </span>
        </div>
        <div class="col-7">
           {!!  $log->getName($user->getKey())  !!}
            @if ($log->getPayload())
                <br>
                <small class="text-muted">
                    {{ implode(', ',$log->getPayload()) }}
                </small>
            @endif
        </div>
        <div class="col-2">
        {{ $log->getIp() }}
        </div>
    </div>
    @empty
        <div class="alert alert-info">
            Логи отсутствуют
        </div>
    @endforelse
    @include('form._pagination',[
            'elements'=>$logs,
        ])
@stop
