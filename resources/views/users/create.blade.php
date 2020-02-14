@extends('layouts.app')

@section('content')
    <div class="container d-flex justify-content-center w-100">
        <div class="w-25">
        {{ Form::open(['url'=>route('users.store'),'files'=>true,'method'=>'POST',]) }}
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
       ])

        @include('form._input',[
          'name'=>'password',
          'type'=>'password',
            'required'=>true,
          'label'=>'Пароль'
      ])

        @include('form._input',[
          'name'=>'password_confirmation',
           'type'=>'password',
             'required'=>true,
          'label'=>'Повторение пароля'
      ])
            <label for="">Аватар</label>
            @include('form._file',[
          'name'=>'image',
           'type'=>'file',
           'label'=>'Загрузите файл',
      ])
            <div class="col-1"></div>
            <div class="col-lg-4">
                <h2>Роли</h2>
                @include('users._roles')
            </div>
        <button class="btn btn-primary w-100 mt-3">Создать</button>
        {{ Form::close() }}
    </div>
    </div>
@stop
