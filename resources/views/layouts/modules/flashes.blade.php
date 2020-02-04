<?php
/**
 * @var array $flashMessages
 *
 * Флеш сообщения всегда приходят массивом. Удалено получение соло-флеш сообщения
 */
$flashMessages = session('flashMessages');
?>
@if (null !== $flashMessages)
    <div id="flash_messages" class="container">
        <div class="row justify-content-center">
                @foreach($flashMessages as $message)
                <div class="col-auto">
                <div class="alert alert-{{ $message['type'] }}  text-center"
                         data-type="{{ $message['type'] }}"
                         aria-live="assertive" role="alert">

                        {!! $message['text'] !!}
                        @isset($message['link'])
                            <a href="{!! $message['link']['url'] !!}">{!! $message['link']['label'] !!}</a>
                        @endisset
                        <div
                            class="position-absolute"
                            style="        top: -10px;
    right: -5px;
}"
                            role="button"
                            data-dismiss="alert"
                            aria-label="Close">
                            <i class="fas fa-times-circle"></i>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
    </div>

@endif
