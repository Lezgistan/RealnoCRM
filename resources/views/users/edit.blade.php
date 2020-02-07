@extends('layouts.app')

@section('content')
    <div class="row justify-content-center">
<div class="col-3">
    <div class="form-group">
        {{ Form::model($user,['url'=>route('users.update',$user),'method'=>'PATCH','files'=>true,]) }}
        <?php
        /**
         * @var \App\Models\Users\User $user
         */
        ?>
        <label for="">Аватар</label>
        @if (isset($user))
            <br>

            <img class="img-fluid" src="{{$user->getImageUrl()}}">
        @else
        @endif
    </div>
    @include('form._file',[
                 'name'=>'image',
                  'type'=>'file',
                  'label'=>'Загрузите файл',
             ])
    </div>
<div class="col-3">
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
    </div>
</div>



            <div class="pt-3 text-center">
                <button class="btn btn-primary">Сохранить</button>
            </div>
            {{ Form::close() }}
@stop
