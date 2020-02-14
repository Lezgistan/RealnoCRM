@extends('layouts.app')

@section('content')
    <div class="row justify-content-center">
        <div class="col-lg-auto col-auto">
            {{ Form::model($user,['url'=>route('users.update',$user),'method'=>'PATCH','files'=>true,]) }}
            <?php
            /**
             * @var \App\Models\Users\User $user
             */
            ?>
            @if (isset($user))
                <div class="text-center justify-content-center">
                <div class="text-center image-hover-wrapper rounded-circle d-inline-block card border-0">
                    <img class="img-fluid rounded-circle bg-avatar card-img" id="avatar" src="{{$user->getImageUrl()}}"
                         alt="Аватарка">
                    <div class="avatar-upload card-img-overlay">
                    @include('form._avatar',[
                    'name'=>'image',
                    'label'=>'Загрузите файл',
                 ])
                    @else
                    @endif
                    </div>
                </div>
                </div>
        </div>
                <div class="col-lg-4 col-8">
                @include('form._input',[
        'name'=>'email',
        'type'=>'email',
        'required'=>true,
        'label'=>'Электронная почта'
    ])
                @include('form._input',[
                'name'=>'l_name',
                  'required'=>true,
                'label'=>'Фамилия'
                ])
                @include('form._input',[
                    'name'=>'f_name',
                    'label'=>'Имя'
                ])
                @include('form._input',[
                   'name'=>'m_name',
                   'label'=>'Отчество'
                ])

                @include('form._input',[
                   'name'=>'age',
                   'label'=>'Возраст',
                   'type'=>'date',
                   'value'=>isset($user) ? $user->age->format('Y-m-d') : null,
                ])
        </div>
    </div>



    <div class="pt-3 text-center">
        <button class="btn btn-primary">Сохранить</button>
    </div>
    {{ Form::close() }}

@stop
