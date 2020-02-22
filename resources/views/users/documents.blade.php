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
        <div class="row border-bottom py-2">
            <div class="col-1">
                №{{ $document->getKey() }}
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
