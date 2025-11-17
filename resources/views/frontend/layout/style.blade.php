<!-- Web Font -->
<link href="https://fonts.googleapis.com/css?family=Poppins:200i,300,300i,400,400i,500,500i,600,600i,700,700i,800,800i,900,900i&display=swap"
    rel="stylesheet">
<!-- Magnific Popup -->
<link rel="stylesheet" href="{{asset('assets/frontend/css/magnific-popup.min.css')}}">
<!-- Font Awesome -->
<link rel="stylesheet" href="{{asset('assets/frontend/css/font-awesome.css')}}">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" crossorigin="anonymous">

<!-- Fancybox -->
<link rel="stylesheet" href="{{asset('assets/frontend/css/jquery.fancybox.min.css')}}">
<!-- Themify Icons -->
<link rel="stylesheet" href="{{asset('assets/frontend/css/themify-icons.css')}}">
<!-- Nice Select CSS -->
<link rel="stylesheet" href="{{asset('assets/frontend/css/niceselect.css')}}">
<!-- Animate CSS -->
<link rel="stylesheet" href="{{asset('assets/frontend/css/animate.css')}}">
<!-- Flex Slider CSS -->
<link rel="stylesheet" href="{{asset('assets/frontend/css/flex-slider.min.css')}}">
<!-- Slicknav -->
<link rel="stylesheet" href="{{asset('assets/frontend/css/slicknav.min.css')}}">
<!-- StyleSheets -->
<link rel="stylesheet" href="{{asset('assets/frontend/css/reset.css')}}">
<link rel="stylesheet" href="{{asset('assets/frontend/style.css')}}">
<link rel="stylesheet" href="{{asset('assets/frontend/css/responsive.css')}}">
<!-- Owl Carousel -->
<link rel="stylesheet" href="{{asset('assets/frontend/css/owl-carousel.css')}}">
<!-- Custom StyleSheet -->
{{-- Variation Button In Product--}}
<style>
    .select_variant_btn {
        cursor: pointer;
    }

    .select_variant_btn input {
        position: absolute;
        opacity: 0;
    }

    .selected {
        background-color: #007bff; /* Change this to your desired selected color */
        color: #fff;
    }
    .whatsapp-chat {
        position: fixed;
        bottom: 20px;
        left: 20px;
        z-index: 10000;
    }

    .whatsapp {
        display: block;
        width: 60px;
        height: 60px;
        line-height: 60px;
        background-color: #25d366;
        color: white !important;
        text-align: center;
        border-radius: 50%;
        font-size: 24px;
        transition: background-color 0.3s ease;
    }

    .whatsapp:hover {
        background-color: darkgreen;
    }

</style>
<style>
    .bottom-border {
        border-bottom: 3px solid #dee2e6; /* You can adjust the color as needed */
        padding-bottom: 10px; /* Optional: Add padding for spacing */
        margin-bottom: 10px; /* Optional: Add margin for additional spacing */
    }
</style>
<style>
    @media screen and (-webkit-min-device-pixel-ratio: 0) {
        input[type='range'] {
            overflow: hidden;
            -webkit-appearance: none;
            background-color: gray;
        }

        input[type='range']::-webkit-slider-runnable-track {
            height: 10px;
            -webkit-appearance: none;
            color: #ec1a25;
            margin-top: -1px;
        }

        input[type='range']::-webkit-slider-thumb {
            width: 10px;
            -webkit-appearance: none;
            height: 10px;
            cursor: ew-resize;
            background: #434343;
            box-shadow: -80px 0 0 80px #ec1a25;
        }

    }

    .alert {
        position: fixed !important;
        z-index: 100 !important;
        bottom: 0;
    }

</style>

@if(app()->getLocale() == 'ar')
    <!-- Bootstrap -->
    <style>
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

@endif

{{get_setting('web_custom_css')}}

{{get_setting('header_script')}}
