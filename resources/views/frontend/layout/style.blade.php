<!-- Web Font -->
<link href="https://fonts.googleapis.com/css?family=Poppins:200i,300,300i,400,400i,500,500i,600,600i,700,700i,800,800i,900,900i&display=swap"
    rel="stylesheet">
<!-- Bootstrap -->
<link rel="stylesheet" href="{{asset('assets/frontend/css/bootstrap.css')}}">
<!-- Magnific Popup -->
<link rel="stylesheet" href="{{asset('assets/frontend/css/magnific-popup.min.css')}}">
<!-- Font Awesome -->
<link rel="stylesheet" href="{{asset('assets/frontend/css/font-awesome.css')}}">
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
<!-- Owl Carousel -->
<link rel="stylesheet" href="{{asset('assets/frontend/css/owl-carousel.css')}}">
<!-- Slicknav -->
<link rel="stylesheet" href="{{asset('assets/frontend/css/slicknav.min.css')}}">
<!-- Eshop StyleSheet -->
<link rel="stylesheet" href="{{asset('assets/frontend/css/reset.css')}}">
<link rel="stylesheet" href="{{asset('assets/frontend/style.css')}}">
<link rel="stylesheet" href="{{asset('assets/frontend/css/responsive.css')}}">
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

{{get_setting('web_custom_css')}}

{{get_setting('header_script')}}
