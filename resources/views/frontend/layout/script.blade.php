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
    if ($('#lang-change').length > 0) {
        $('#lang-change .dropdown-menu a').each(function() {
            $(this).on('click', function(e) {
                e.preventDefault();
                var $this = $(this);
                var locale = $this.data('flag');
                $.post('{{ route('language.change') }}', {
                    _token: '{{ csrf_token() }}',
                    locale: locale
                }, function(data) {
                    location.reload();
                });

            });
        });
    }
    $(document).ready(function() {
        // Create an array of items
        var li = $('<li>').html(`
            <a href="#" role="menuitem" tabindex="0">{{translate('all_categories')}}</a>
            `);
        // Append the ul element to the element with class "slicknav_nav"
        $('.slicknav_nav').append(li);

        var subcategories = @json($settings['all_categories']);

        var subUl = $('<ul>');

        // Iterate through the items array and create li elements for each item
        $.each(subcategories, function(index, subcategory) {
            var subLi = $('<li>').append($(`<a >`, {
                href: '/all-sub_category/' + subcategory.slug,
                text: subcategory.name,
                role: 'menuitem',
                tabindex: '0'
            }));
            subUl.append(subLi);
        });

        // Append the sub ul element to the li element with the text "Categories"
        $('.slicknav_nav').find('li:contains("Categories")').append(subUl);
    });

</script>



{{get_setting('footer_script')}}
