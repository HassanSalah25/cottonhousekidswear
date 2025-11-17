{{--Cart Ajax--}}
<script>
    const temp_id = '{{ session()->getId()}}'
    indexCart()

    function show_alert(msg) {
        var alert_container = $('#alerting');
        var alert_html = `<div class="alert alert-danger" role="alert" >${msg}</div>`;
        alert_container.empty()
        alert_container.append(alert_html).delay(3000).fadeOut(350, function () {
            $(this).empty(); // Remove the alert content after fading out
        });

    }

    function indexCart() {
        const sinlge_bar = $('.sinlge-bar');
        const cartContainer = $('.shopping-list');
        const dropdown_cart_header = $('.dropdown-cart-header');
        const bottom = $('.bottom');
        cartContainer.empty();
        $.ajax({
            url: '{{url('/api/v1/carts')}}',
            data: {
                @if(auth()->user())
                user_id: {{ auth()->user()->id}},
                @else
                temp_user_id: temp_id,
                @endif
            },
            method: 'POST',
            dataType: 'json', // Specify the expected data type
            success: function (response) {
                // The request was successful, handle the response here

                var cart_items = response.cart_items.data;
                var cart_items_count = response.cart_items_count;
                var total_price = response.total_price;
                sinlge_bar.find('.total-count').text(cart_items_count);
                bottom.find('.total-amount').text(total_price + ' {{$settings['general_settings']['currency']['code']}}');
                dropdown_cart_header.find('span').text(cart_items_count);
                cart_items.forEach(function (item) {
                    var html_item = `
                            <li>
                                <a onclick="deleteItemFromCart(this,${item['cart_id']})" class="remove" title="Remove this item"><i class="fa fa-remove"></i></a>
                                <a class="cart-img" href="#"><img src="${item['thumbnail']}"
                                                                  alt="#"></a>
                                <h4><a href="#">${item['name']}</a></h4>
                                <p class="quantity">${item['qty']}X - <span class="amount">${item['dicounted_price']} {{$settings['general_settings']['currency']['code']}}</span> </span></p>

                            </li>
                        `;
                    cartContainer.append(html_item)
                });

            },
            error: function (error) {
                // Handle errors here

            }
        });

    }

    function addItemToCart(product_variation_id) {
        const cartContainer = $('.shopping-list');
        var product_variation_id = null;
        if (!product_variation_id) {
            product_variation_id = $('.select_variant_btn.selected').find('.variation-radio').val();
        }
        var quantity = $('#quantityInput').val();
        $.ajax({
            url: '{{url('/api/v1/carts/add')}}',
            headers: {
                'Accept-Language':'{{app()->getLocale()}}'
            },
            data: {
                variation_id: product_variation_id,
                @if(auth()->user())
                user_id: {{ auth()->user()->id}},
                @else
                temp_user_id: temp_id,
                @endif
                qty: quantity,
            },
            method: 'POST',
            dataType: 'json', // Specify the expected data type
            success: function (response) {
                console.log(response)
                indexCart()
            },
            error: function (error) {
                // Handle errors
                console.log(error.responseJSON.message)
                show_alert(error.responseJSON.message)
            }
        });

    }

    function deleteItemFromCart(self, cartId) {
        $(self).parent().remove();
        const cartContainer = $('.shopping-list');
        $.ajax({
            url: '{{url('/api/v1/carts/destroy')}}',
            data: {
                @if(auth()->user())
                user_id: {{ auth()->user()->id}},
                @else
                temp_user_id: temp_id,
                @endif
                cart_id: cartId
            },
            method: 'POST',
            dataType: 'json', // Specify the expected data type

            success: function (response) {
                // The request was successful, handle the response here
                indexCart()

            },
            error: function (error) {
                // Handle errors here

            }
        });
    }

</script>
{{--Wishlist Ajax--}}
<script>
    var count = {{auth()->user() ? auth()->user()->wishlists->count() : 0}};

    function indexWishlist() {
        const sinlge_bar = $('.sinlge-bar');
        const cartContainer = $('.shopping-list');
        const dropdown_cart_header = $('.dropdown-cart-header');
        const bottom = $('.bottom');
        cartContainer.empty();
        $.ajax({
            url: '{{url('/user/wishlists')}}',
            data: {},
            method: 'GET',
            dataType: 'json', // Specify the expected data type
            success: function (response) {
                // The request was successful, handle the response here

                var cart_items = response.cart_items.data;
                var cart_items_count = response.cart_items_count;
                var total_price = response.total_price;
                sinlge_bar.find('.total-count').text(cart_items_count);
                bottom.find('.total-amount').text(total_price + ' {{$settings['general_settings']['currency']['code']}}');
                dropdown_cart_header.find('span').text(cart_items_count);
                cart_items.forEach(function (item) {
                    var item = `
                            <li>
                                <a onclick="deleteItemFromCart(this,${item['cart_id']})" class="remove" title="Remove this item"><i class="fa fa-remove"></i></a>
                                <a class="cart-img" href="#"><img src="${item['thumbnail']}"
                                                                  alt="#"></a>
                                <h4><a href="#">${item['name']}</a></h4>
                                <p class="quantity">${item['qty']}X - <span class="amount">${item['dicounted_price']}</span></p>
                            </li>
                        `;
                    cartContainer.append(item)
                });

            },
            error: function (error) {
                // Handle errors here

            }
        });

    }

    function addItemToWishlist(self, product_id) {

        var icon = $(self);
        $.ajax({
            url: '{{url('/user/wishlists')}}',
            data: {
                _token: '{{ csrf_token() }}',
                product_id: product_id,
            },
            method: 'POST',
            dataType: 'json', // Specify the expected data type
            success: function (response) {
                var totalElement = $('.total-count2')
                icon.css('color', '#ec1a25')
                icon.removeAttr('onclick')
                icon.attr('onclick', `deleteItemFromWishlist(this,${product_id})`)


                // Increment the count
                count++;
                totalElement.text(count)
            },
            error: function (error) {
                // Handle errors here

            }
        });

    }

    function deleteItemFromWishlist(self, product_id) {

        var icon = $(self);
        $.ajax({
            url: '{{url('/user/wishlists/destroy/')}}',
            data: {
                _token: '{{ csrf_token() }}',
                product_id: product_id,
            },
            method: 'DELETE',
            dataType: 'json', // Specify the expected data type
            success: function (response) {
                var totalElement = $('.total-count2')
                var icon = $(self);
                icon.css('color', '#000')
                icon.removeAttr('onclick')
                icon.attr('onclick', `addItemToWishlist(this,${product_id})`)
                // Increment the count
                count--;

                totalElement.text(count)
            },
            error: function (error) {
                // Handle errors here

            }
        });
    }
</script>
{{--Newsettler Ajax--}}
<script>
    function subscribe() {
        var subscription_mail = $('#subscription_email')
        $.ajax({
            url: '{{url('/api/v1/subscribe')}}',
            data: {
                email: subscription_mail.val(),
            },
            method: 'POST',
            dataType: 'json', // Specify the expected data type
            success: function (response) {
                // The request was successful, handle the response here

                subscription_mail.val('')
            },
            error: function (error) {
                // Handle errors here
            },

        });
    }
</script>
{{--handling profile img--}}
<script>
    function previewImage(input) {
        var file = input.files[0];

        if (file) {
            var reader = new FileReader();

            reader.onload = function (e) {
                $('#profilePic').attr('src', e.target.result);
            };

            reader.readAsDataURL(file);
        }
    }

    function deleteImg() {
        var image = $('#profilePic')
        var img_profile = $('#img_profile')
        $.ajax({
            url: '{{url('/api/v1/user/info/remove/image')}}',
            data: {
                '_token': "{{csrf_token()}}",
                'user_id': "{{auth()->user()?->id}}"
            },
            method: 'POST',
            dataType: 'json', // Specify the expected data type
            success: function (response) {
                image.attr('src', 'https://icon-library.com/images/default-user-icon/default-user-icon-8.jpg')
                img_profile.val('')
            },
            error: function (error) {
                // Handle errors here
            },

        });
    }
</script>
{{--selected tab profile--}}
<script>
    $(document).ready(function () {
        // Get the current URL
        var currentUrl = new URLSearchParams(window.location.search);
        var nav = $('.nav-div');
        if (currentUrl.get('tab')) {
            var tab = nav.find('#' + currentUrl.get('tab'))
            var content = nav.find('#' + currentUrl.get('tab').replace('-tab', ""))
            tab.addClass('active')
            content.addClass('show active')
        } else {
            var tab = nav.find('#v-pills-profile-tab')
            var content = nav.find('#v-pills-profile')
            tab.addClass('active')
            content.addClass('show active')
        }
    });
</script>
{{--selected variants--}}
<script>
    $(document).ready(function () {
        // Add click event handler to toggle selected class
        $('.select_variant_btn').on('click', function () {
            $('.select_variant_btn').removeClass('selected');
            $(this).addClass('selected');

            // Update the selected radio button
            var radio = $(this).find('.variation-radio');
            radio.prop('checked', true);
        });
    });
</script>

<script>
    // Update value displays dynamically
    $('#range1').on('input', function () {
        $('#value1').val(this.value);
    });

    $('#range2').on('input', function () {
        $('#value2').val(this.value);
    });
    // Add more event listeners for additional range inputs
    $(".toggleButton").on("click", function() {
        var div = $(".side_bar");
        div.toggleClass("visible");
    });

</script>


