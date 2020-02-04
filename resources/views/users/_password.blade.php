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
