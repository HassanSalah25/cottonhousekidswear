<!DOCTYPE html>
<html lang="">
<head>
    <!-- Meta Tag -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name='copyright' content=''>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{$settings['appLogo']}}">
    <!-- Title Tag  -->
    <title>{{$settings['appName']}}</title>
{{-- Stylesheets--}}
    @include('frontend.layout.style')
{{--    Custom Style On Single Component--}}
    @stack('styles')
</head>
<body class="js">
{{--    flash message--}}
    <div class="d-flex justify-content-center" id="alerting">
        @include('flash::message')
    </div>
{{--    preloader Component--}}
    @include('frontend.partials.components.preloader')
{{--    Header Layout--}}
    @include('frontend.layout.header')
{{--    Content Section--}}
        @yield('content')
{{--    Footer Layout--}}
    @include('frontend.layout.footer')
{{--    Scripts--}}
    @include('frontend.layout.script')
{{--    Custom Script On Single Component--}}
    @stack('scripts')
</body>
</html>
