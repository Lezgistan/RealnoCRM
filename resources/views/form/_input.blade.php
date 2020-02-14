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

<div class="form-group">
    @if (isset($label))
        <label for="basic-url">{{ $label ?? null }}&nbsp;</label>
    @endif

    {{ Form::input( $type ?? 'text',$name,$value ?? null,[
    'class'=>'form-control '.($errors->has($name) ? ' is-invalid ' : null),
    'required'=>$required ?? null,
    'placeholder'=>$placeholder ?? null,
    ]) }}
    @if (isset($text))
        <small class="form-text text-muted">{!! $text !!}</small>
    @endif

    @if($errors->has($name) === true)
        <div class="invalid-feedback">{{ $errors->first($name) }}</div>
    @endif
</div>



