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


    <div class="custom-file">
    @if (isset($label))
        <label for="customFile" class="custom-file-label">{{ $label ?? null }}&nbsp;</label>
    @endif

    {{ Form::input( $type,$name,$value ?? null,[
    'class'=>'custom-file-input'.($errors->has($name) ? ' is-invalid ' : null),
    'id'=>'customFile',
    'required'=>$required,
    'placeholder'=>$placeholder ?? null,
    ]) }}
    @if (isset($text))
        <small class="form-text text-muted">{!! $text !!}</small>
    @endif

    @if($errors->has($name) === true)
        <div class="invalid-feedback">{{ $errors->first($name) }}</div>
    @endif
</div>




