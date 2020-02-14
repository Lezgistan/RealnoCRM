<?php

if (isset($name)) {
    if (isset($frd, $name)) {
        $frdVal = $frd[$name] ?? null;
    }
    $type = $type ?? 'text';

    /**
     * РљРѕРЅСЃС‚СЂСѓРєС†РёСЏ РґР»СЏ СЂР°Р±РѕС‚С‹ СЃ РёРјРµРЅР°РјРё РјР°СЃСЃРёРІРѕРІ РІРёРґР°: example[first_item][last_item]
     */
    $nameDot = strpos($name, '[') !== false ? str_replace(['[]', '[', ']'], ['', '.', ''], $name) : $name;
    $value = $value ?? (isset($frd) ? \Illuminate\Support\Arr::get($frd, $nameDot) : old($name));
    $value = $value ?? $frdVal ?? old($name);
}

?>
        {{ Form::input( $type ='file',$name,$value ?? null,[
            'id'=>'file',
            'class'=>' d-none '.($errors->has($name) ? ' is-invalid ' : null),
            'required'=>$required ?? null,
            'placeholder'=>$placeholder ?? null,
            ]) }}
        {{ Form::button($value='<i class="fas fa-upload"></i>',[
            'onclick'=>'thisFileUpload();',
            'id'=>'button',
            'name'=>'button',
            'class'=>' btn h-100 w-100 upload-button '.($errors->has($name) ? ' is-invalid ' : null),
            'placeholder'=>$placeholder ?? null,
            ]) }}

    @if (isset($text))
        <small class="form-text text-muted">{!! $text !!}</small>
    @endif


    @if($errors->has($name) === true)
        $flashMessages = [['type' => 'success', 'text' => 'Пользователь «' . $user->getName() . '» сохранен']];

    @endif
