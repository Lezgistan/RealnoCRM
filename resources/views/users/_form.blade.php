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
          'name'=>'image',
           'type'=>'file',
             'required'=>true,
          'label'=>'Изображение'
      ])



