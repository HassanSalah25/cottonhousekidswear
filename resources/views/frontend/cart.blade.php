@extends('frontend.layout.app')
@push('styles')
    <style>
        .nice-select.open .list{
            overflow: scroll;
        }
    </style>
@endpush
@section('content')
    <div class="container-fluid">
        <div class="row m-5">
            <div class="row col-12">
                <h4 class="text-dark">{{translate('Address')}}</h4>
            </div>
            <div class="row col-12">
                <div class="col-md-6 mt-3 address_info"
                >
                    @if(auth()->user()->addresses->last())

                        <p class="text-dark">{{auth()->user()->addresses->last()?->address}}</p>
                    @else
                        <p class="text-dark">{{translate('No Address Found')}}</p>

                    @endif
                </div>
                <button href="#" class="btn btn-light text-light rounded" data-target="#address_modal"
                   data-toggle="modal">{{translate('Change Address')}}</button>
                <div class="col-md-3"
                >
                </div>
            </div>
        </div>

        <form id="form2" action="{{route('checkout.order.store')}}" method="POST">
            @csrf
            <div class="row">
                <input name="shipping_address_id" type="hidden" value="{{auth()->user()->addresses->last()?->id}}">
                <input name="delivery_type" type="hidden" value="standard">
                <input name="payment_type" type="hidden" value="cash_on_delivery">
                <div class="col-md-8">
                    <div class="card p-4" id="cards">
                        <h5 class="text-dark my-3">{{$cart_items_count}} {{translate('Items')}}</h5>
                        @foreach($carts as $cart_item)
                            <input name="cart_item_ids[]" type="hidden" value="{{$cart_item->id}}">
                            <div class="row my-2">
                                <div class="col-md-3">
                                    <div class="overlay">
                                        <img src="{{api_asset($cart_item->product->thumbnail_img)}}"
                                             class="zoom-in figure-img img-fluid">
                                    </div>
                                </div>
                                <div class="col-md-7">
                                    <h5 class="text-dark pt-2">{{$cart_item->product->getTranslation('name')}}</h5>
                                    {{--                            <p class="text-uppercase font-weight-light">SHIRT - RED</p>--}}
                                    <div class="linespace mb-4">
                                        @foreach($cart_item->variation->combinations as $combine)
                                            <p class="text-dark mt-4">{{$combine->attribute->getTranslation('name')}}
                                                : {{$combine->attribute_value->getTranslation('name')}}</p>
                                        @endforeach
                                    </div>
                                    <a href="{{route('cart.destroy',$cart_item)}}"
                                       class="btn btn-sm rounded text-uppercase btn-light"><i class="fa fa-trash pr-2"></i>{{translate('REMOVE ITEM')}}
                                    </a>
                                    &nbsp;

                                    @auth
                                        @if(in_array($cart_item->product->id,auth()->user()?->wishlists()->pluck('product_id')->toArray()))
                                            <a title="Wishlist"
                                               onclick="deleteItemFromWishlist(this,{{$cart_item->product->id}})"
                                               class="btn btn-sm rounded text-uppercase btn-light"
                                               style="color: #ec1a25"
                                            ><i class=" px-1 fa fa-heart"></i><span>{{translate('Add to Wishlist')}}</span></a>
                                        @else
                                            <a title="Wishlist"
                                               onclick="addItemToWishlist(this,{{$cart_item->product->id}})"
                                               class="btn btn-sm rounded text-uppercase btn-light"
                                               style="color: white">
                                                <i class=" px-1 fa fa-heart"></i><span>{{translate('Add to Wishlist')}}</span></a>
                                        @endif
                                    @else
                                        <a title="Wishlist" href="{{route('customer.login')}}"
                                           class="btn btn-sm rounded text-uppercase btn-light"
                                           style="color: white">
                                            <i class=" px-1 fa fa-heart"></i><span>{{translate('Add to Wishlist')}}</span>
                                        </a>
                                    @endauth

                                </div>

                                <div class="col-md-2">
                                    <div class="input-group input-group-sm">
                                        <input type="number" class="text-center border border-danger form-control"
                                               value="{{$cart_item->quantity}}">
                                    </div>
                                </div>
                            </div>
                        @endforeach
                        <hr>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card p-3" id="card-cost">
                        <h5 class="text-dark pl-2">{{translate('Total Amount')}}</h5>
                        <table class="table table-borderless pt-2">

                            <tbody>
                            <tr>
                                <th scope="row" class="font-weight-light">{{translate('Products Amount')}}</th>
                                <td>{{$shop_subTotal}} {{$settings['general_settings']['currency']['code']}}</td>
                            </tr>
                            <tr>
                                <th scope="row" class="font-weight-light">{{translate('Shipping')}}</th>
                                <td>{{$standard_delivery_cost}} {{$settings['general_settings']['currency']['code']}}</td>
                            </tr>
                            <tr>
                                <th scope="row" class="font-weight-light">{{translate('Taxes')}}</th>
                                <td>{{$shop_tax}} {{$settings['general_settings']['currency']['code']}}</td>
                            </tr>
                            {{--<tr>
                                <th scope="row" class="font-weight-light">Shipping</th>
                                <td>$100.00</td>
                            </tr>--}}
                            </tbody>
                        </table>
                        <hr>
                        <h6 class="pl-2 pt-1">{{translate('Total Price')}} <span
                                class="float-right">{{$shop_total}} {{$settings['general_settings']['currency']['code']}}</span>
                        </h6>
                        <hr>

                        <button class="btn btn-primary mt-2 rounded">
                            <i></i>{{translate('Proceed to Checkout')}}
                        </button>
                        <a class="btn btn-primary mt-2 rounded text-light text-center" data-target="#coupon_modal"
                                data-toggle="modal">
                            <i></i>{{translate('Check Coupon')}}
                        </a>
                    </div>
                </div>
            </div>

        </form>


        <div id="coupon_modal" class="modal fade" role="dialog">
            <div class="modal-dialog modal-dialog-centered modal-dialog-zoom" role="document">
                <div class="modal-content">
                    <div class="modal-header bord-btm">
                        <h4 class="modal-title h6">{{translate('Coupon')}}</h4>
                        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                    <div class="row justify-content-center">
                        <div class="row align-items-end justify-content-center m-5 border border-danger p-5 rounded">
                            <form id="form1" action="{{route('coupon.apply')}}" method="POST">
                                @csrf
                                <div class="row">
                                    <h6 class="my-3">{{translate('Have a Coupon Code? Enter Below')}}</h6>
                                    <input type="text" name="coupon_codes[]"
                                           style="border-bottom: 1px solid #ccc !important;"
                                           class="form-control border-0 pl-2"
                                           placeholder="x x x x x x">
                                </div>
                                <div class="row w-100 mt-2">
                                    <button  type="submit" class="btn btn-primary rounded w-100">
                                        {{translate('Apply')}}
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                    </div>
                </div>
            </div>
        </div>
        {{--<div class="row mt-5 mb-5">
            <div class="col-md-8">
                <div class="card p-3">
                    <h5 class="text-dark">{{translate('Expected shipping delivery')}}</h5>
                    <p class="text-dark"></p>

                </div>
            </div>
        </div>--}}

        <!-- new address modal -->
        <div id="address_modal" class="modal fade" role="dialog">
            <div class="modal-dialog modal-dialog-centered modal-dialog-zoom" role="document">
                <div class="modal-content">
                    <div class="modal-header bord-btm">
                        <h4 class="modal-title h6">{{translate('Shipping Address')}}</h4>
                        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form class="form-horizontal" action="{{route('addresses.store')}}" method="POST"
                          enctype="multipart/form-data">
                        @csrf
                        <div class="modal-body">
                            <input type="hidden" name="customer_id" id="set_customer_id" value="{{auth()->user()->id}}">
                            <div class="form-group">
                                <div class=" row">
                                    <label class="col-sm-2 control-label" for="address">{{translate('Address')}}</label>
                                    <div class="col-sm-8">
                                        <textarea placeholder="{{translate('Address')}}" id="address" name="address"
                                                  class="form-control" required></textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="row">
                                    <label class="col-sm-2 control-label">{{translate('Country')}}</label>
                                    <div class="col-sm-8">
                                        <select class="form-control aiz-selectpicker" data-live-search="true"
                                                data-placeholder="{{ translate('Select your country') }}"
                                                name="country_id" required>
                                            <option value="">{{ translate('Select your country') }}</option>
                                            @foreach (\App\Models\Country::where('status', 1)->get() as $key => $country)
                                                <option value="{{ $country->id }}">{{ $country->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-sm-2 control-label">
                                        <label>{{ translate('State')}}</label>
                                    </div>
                                    <div class="col-sm-8">
                                        <select class="form-control mb-3" data-live-search="true" name="state_id"
                                                required>
                                            <option value="">{{translate("Select State")}}</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-sm-2">
                                        <label>{{ translate('City')}}</label>
                                    </div>
                                    <div class="col-sm-8">
                                        <select class="form-control mb-3" data-live-search="true" name="city_id"
                                                required>
                                            <option value="">{{translate("Select City")}}</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class=" row">
                                    <label class="col-sm-2 control-label"
                                           for="postal_code">{{translate('Postal code')}}</label>
                                    <div class="col-sm-4">
                                        <input type="text" min="0" placeholder="{{translate('Postal code')}}"
                                               id="postal_code" name="postal_code" class="form-control p-1" required>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class=" row">
                                    <label class="col-sm-2 control-label" for="phone">{{translate('Phone')}}</label>
                                    <div class="col-sm-4">
                                        <input type="text" min="0" placeholder="{{translate('Phone')}}" id="phone"
                                               name="phone" class="form-control p-1 " required>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-styled btn-base-3"
                                    data-dismiss="modal">{{translate('Close')}}</button>
                            <button type="submit"
                                    class="btn btn-primary btn-styled btn-base-1">{{translate('Save')}}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>


    </div>
@endsection

@push('scripts')
    <script>
        //address
        $(document).on('change', '[name=country_id]', function () {
            var country_id = $(this).val();
            get_states(country_id);
        });

        $(document).on('change', '[name=state_id]', function () {
            var state_id = $(this).val();
            get_city(state_id);
        });

        function get_states(country_id) {
            $('[name="state_id"]').html("");
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "{{route('get-state')}}",
                type: 'POST',
                data: {
                    country_id: country_id
                },
                success: function (response) {
                    var obj = JSON.parse(response);
                    if (obj != '') {
                        console.log(obj);
                        $('[name="state_id"]').html(obj);
                        $('select').niceSelect();
                        $('select').niceSelect('update');
                    }
                }
            });
        }

        function get_city(state_id) {
            $('[name="city_id"]').html("");
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "{{route('get-city')}}",
                type: 'POST',
                data: {
                    state_id: state_id
                },
                success: function (response) {
                    var obj = JSON.parse(response);
                    if (obj != '') {
                        $('[name="city_id"]').html(obj);
                        $('select').niceSelect();
                        $('select').niceSelect('update');
                    }
                }
            });
        }
    </script>
@endpush
