<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    @include('partial.head')
    @include('partial.css')
    @laravelPWA
    @stack('style')
    @vite('resources/js/app.js')
</head>

<body class="{{ (Request::segments()[0] == "home" ? 'toggle-sidebar' : '') }}">
    @include('partial.header')
    @include('partial.sidebar')
    <main id="main" class="main">
        @yield('content')
    </main>
    @include('partial.footer')
    @include('partial.js')
    @yield('script')
</body>
</html>
