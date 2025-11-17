<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}"
      dir="{{str_replace('_', '-', app()->getLocale()) == 'ar' ? 'rtl' : 'ltr'}}"
>

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
    @if(app()->getLocale() == 'ar')
        <!-- Bootstrap -->
        <link rel="stylesheet" href="{{asset('assets/css/bootstrap-rtl.min.css')}}">
        <style>
            .nice-select{
                float: right !important;
            }
            .address_info{
                border-right: 5px solid red !important;
                border-left: 0px !important;
            }
            .header.shop .cat-heading i {
                margin-left: 15px;
            }


            #productPage {
                direction: rtl;
            }

            .header-inner {
                direction: rtl;
            }

            .header.shop .nav-inner {
                float: right !important;
            }

            .header.shop .right-content {
                float: left !important;
            }

            .header.shop .right-bar .sinlge-bar:last-child {
                margin-right: 25px !important;
            }

            .header.shop .main-category li a i {

                float: left !important;
            }

            .shop-newsletter .newsletter-inner input {
                border: 1px solid #ececec !important;
                border-radius: 0 30px 30px 0 !important;
            }

            .shop-newsletter .newsletter-inner .btn {
                right: -4px;
                left: 0px !important;
                border-radius: 30px 0 0 30px !important;

            }

            .header.shop .search-bar .btnn {

                position: static !important;
                border-radius: 5px 0 0 5px !important;
                left: 159px;
                top: -1px;
            }

            .header.shop .nice-select {
                float: right !important;
                border-left: 1px solid #eee !important;
            }

            .nice-select .list {
                right: 0 !important;
                left: auto !important;
            }
            .header.shop .main-category li .mega-menu{

                width: 100%;
                display: inline-block;
                height: auto;
                position: absolute;
                right: 263px;
                top: 0;
                z-index: 99999;
                background: #fff;
                border: none;
                padding: 30px;
                box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
                border-right: 3px solid #ec1a25 !important;
                border-left: 0px !important;
                opacity: 0;
                visibility: hidden;
                -webkit-transition: all 0.4s ease;
                -moz-transition: all 0.4s ease;
                transition: all 0.4s ease;
            }
            .fa-angle-right:before {
                content: "\f104" !important;
            }
            .owl-carousel{
                direction: ltr !important;
            }
             .nice-select .list li{
                text-align: start !important;
            }
            .nice-select{
                text-align: start !important;
            }
            .nice-select:after {
                border-bottom: 2px solid #999;
                border-right: 2px solid #999;
                content: '';
                display: block;
                height: 8px;
                margin-top: -4px;
                pointer-events: none;
                position: absolute;
                right: auto !important;
                left: 5px !important;
                top: 50%;
                -webkit-transform-origin: 66% 66%;
                -ms-transform-origin: 66% 66%;
                transform-origin: 66% 66%;
                -webkit-transform: rotate(45deg);
                -ms-transform: rotate(45deg);
                transform: rotate(45deg);
                -webkit-transition: all 0.15s ease-in-out;
                transition: all 0.15s ease-in-out;
                width: 8px;
            }
            .header .shopping .shopping-item {
                position: absolute;
                top: 68px;
                right: auto !important;
                left: 0;
                width: 300px;
                background: #fff;
                padding: 20px 25px;
                -webkit-transition: all 0.3s ease 0s;
                -moz-transition: all 0.3s ease 0s;
                transition: all 0.3s ease 0s;
                -webkit-transform: translateY(10px);
                -moz-transform: translateY(10px);
                transform: translateY(10px);
                -webkit-box-shadow: 0px 0px 5px rgba(0, 0, 0, 0.2);
                -moz-box-shadow: 0px 0px 5px rgba(0, 0, 0, 0.2);
                box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.2);
                opacity:0;
                visibility:hidden;
                z-index:99;
            }
            .header .shopping .dropdown-cart-header a{
                float: left !important;
            }
            .product-price{
                direction: ltr !important;
            }
            .shop-home-list .shop-section-title h1::before{
                left: auto !important;
            }
        </style>
    @else
        <!-- Bootstrap -->
        <link rel="stylesheet" href="{{asset('assets/frontend/css/bootstrap.css')}}">
    @endif
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
<!-- WhatsApp Chat Icon -->
@include('frontend.partials.components.chat_sticky_icon')
{{--    Modal Section--}}
@stack('modal')
{{--    Footer Layout--}}
@include('frontend.layout.footer')
{{--    Scripts--}}
@include('frontend.layout.script')
{{--    Custom Script On Single Component--}}
@stack('scripts')
</body>
</html>
