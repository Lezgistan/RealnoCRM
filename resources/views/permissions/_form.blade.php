@include('form._input',[
      'name'=>'name',
      'label'=>'Системное имя'
  ])

@include('form._input',[
'name'=>'display_name',
  'required'=>true,
'label'=>'Имя'
])

@include('form._input',[
    'name'=>'description',
    'label'=>'Описание'
])
