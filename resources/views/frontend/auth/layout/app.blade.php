<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{$settings['appLogo']}}">
    <title>{{$settings['appName']}}</title>
    <!-- Bootstrap -->
    <link rel="stylesheet" href="{{asset('assets/frontend/css/bootstrap.css')}}">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{asset('assets/frontend/css/font-awesome.css')}}">

    <link rel="stylesheet" type="text/css" href="{{asset('assets/css/iofrm-style.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('assets/css/iofrm-theme2.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('assets/css/iofrm-theme3.css')}}">
    <link rel="stylesheet" href="{{ static_asset('assets/css/aiz-core.css') }}">
    <link rel="stylesheet" href="{{asset('assets/css/bootstrap-rtl.min.css')}}">
    <style>
        .alert {
            position: fixed !important;
            z-index: 100 !important;
            bottom: 0;
        }
    </style>
</head>
<body>
<div class="d-flex justify-content-center" id="alerting">
    @include('flash::message')
</div>
@yield('content')

@include('frontend.layout.script')
<script src="{{asset('assets/js/main.js')}}"></script>
<script src="{{ static_asset('assets/js/aiz-core.js') }}"></script>
<script src="{{asset('assets/frontend/js/bootstrap.min.js')}}"></script>


</script>
</body>
</html>
