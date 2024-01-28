<!-- Jquery -->
<script src="{{asset('assets/frontend/js/jquery.min.js')}}"></script>
<script src="{{asset('assets/frontend/js/jquery-migrate-3.0.0.js')}}"></script>
<script src="{{asset('assets/frontend/js/jquery-ui.min.js')}}"></script>
<!-- Popper JS -->
<script src="{{asset('assets/frontend/js/popper.min.js')}}"></script>
<!-- Bootstrap JS -->
<script src="{{asset('assets/frontend/js/bootstrap.min.js')}}"></script>
<!-- Color JS -->
{{--<script src="{{asset('assets/frontend/js/colors.js')}}"></script>--}}
<!-- Slicknav JS -->
<script src="{{asset('assets/frontend/js/slicknav.min.js')}}"></script>
<!-- Owl Carousel JS -->
<script src="{{asset('assets/frontend/js/owl-carousel.js')}}"></script>
<!-- Magnific Popup JS -->
<script src="{{asset('assets/frontend/js/magnific-popup.js')}}"></script>
<!-- Waypoints JS -->
<script src="{{asset('assets/frontend/js/waypoints.min.js')}}"></script>
<!-- Countdown JS -->
<script src="{{asset('assets/frontend/js/finalcountdown.min.js')}}"></script>
<!-- Nice Select JS -->
<script src="{{asset('assets/frontend/js/nicesellect.js')}}"></script>
<!-- Flex Slider JS -->
<script src="{{asset('assets/frontend/js/flex-slider.js')}}"></script>
<!-- ScrollUp JS -->
<script src="{{asset('assets/frontend/js/scrollup.js')}}"></script>
<!-- Onepage Nav JS -->
<script src="{{asset('assets/frontend/js/onepage-nav.min.js')}}"></script>
<!-- Easing JS -->
<script src="{{asset('assets/frontend/js/easing.js')}}"></script>
<!-- Active JS -->
<script src="{{asset('assets/frontend/js/active.js')}}"></script>


@include('frontend.layout.custom_script')

<script>
    $('div.alert').not('.alert-important').delay(3000).fadeOut(350);
</script>


{{get_setting('footer_script')}}
