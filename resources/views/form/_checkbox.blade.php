
<?php
if (isset($name)) {
    if (isset($frd, $name)) {
        $frdVal = $frd[$name] ?? null;
    }
    $type = $type ?? 'checkbox';

    /**
     * Конструкция для работы с именами массивов вида: example[first_item][last_item]
     */
    $nameDot = strpos($name, '[') !== false ? str_replace(['[]', '[', ']'], ['', '.', ''], $name) : $name;
    $value = $value ?? (isset($frd) ? \Illuminate\Support\Arr::get($frd, $nameDot) : old($name));
    $value = $value ?? $frdVal ?? old($name);
}
?>

<div class="form-check">

    {{ Form::checkbox($name,$value ?? null,$checked ?? false,[
  'class'=>' form-check-input '.($errors->has($name) ? ' is-invalid ' : null),
  'required'=>$required ?? null,
  'placeholder'=>$placeholder ?? null,
  ]) }}

    @if (isset($label))
        <label class="form-check-label" for="defaultCheck1">
            {!! $label !!}
        </label>
    @endif
    @if (isset($text))
        <small class="form-text text-muted">{!! $text !!}</small>
    @endif

</div>
