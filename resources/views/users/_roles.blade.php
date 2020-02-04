@forelse($roles as $role)
    @include('form._checkbox',[
        'name'=>'roles['.$role->getKey().']',
        'label'=>$role->getDisplayName(),
        'value'=>$role->getKey(),
        'checked'=>isset($user)?$user->hasRole([$role->getName()]):null,
    ])
@empty
    <div class="alert alert-danger">
        Роли еще не созданы
    </div>
@endforelse


