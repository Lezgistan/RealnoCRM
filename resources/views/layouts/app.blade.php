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

@stack('scripts')
</body>
</html>
