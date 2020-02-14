<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    {!! SEO::generate() !!}
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Scripts -->

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->

    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons"
          rel="stylesheet">
    <link rel="stylesheet" href="{{mix('css/app.css')}}">
    <link rel="stylesheet" href="{{asset('css/style.css')}}">
</head>
<body class="bg-transparent">
<div id="app">
    @include('layouts.modules.navbar')
    @include('layouts.modules.breadcrumbs')
    @include('layouts.modules.flashes')
    <main class="py-4 container">

        @yield('content')

    </main>
</div>
<script src="{{ mix('js/app.js') }}" ></script>
<script>
    $('[type=file]').on('change',function () {
    $(this).parent().find('.custom-file-label').html(this.files[0].name);
    })
</script>
<script>
    window.addEventListener('load', function() {
        document.querySelector('input[type="file"]').addEventListener('change', function() {
            if (this.files && this.files[0]) {
                var img = document.getElementById('avatar');  // $('img')[0]
                img.src = URL.createObjectURL(this.files[0]); // set src to blob url
                img.onload = imageIsLoaded;
            }
        });
    });
</script>
<script>
    function thisFileUpload() {
        document.getElementById("file").click();
    };
</script>
<script>
    let xhr = new XMLHttpRequest();
    setInterval(function() {
        xhr.open('GET', '/');
        xhr.send();
    },30000);
</script>
@stack('scripts')
</body>
</html>
