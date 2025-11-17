<!-- Modal -->
<div class="modal fade" id="modal-{{$product->id}}" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document" style="
    max-width: 1145px;">
        <div class="modal-content" >
            <div class="modal-header">
                <button type="button" class="close"
                        data-dismiss="modal" aria-label="Close">
                    <span class="ti-close"
                          aria-hidden="true"></span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row no-gutters">
                    <div class="row">
                        <div class="col-md-5">
                            <div class="ecommerce-gallery" data-mdb-zoom-effect="true" data-mdb-auto-height="true">
                                <div class="row py-3 shadow-5">
                                    <div class="col-md-12 mb-1">
                                        <div class="swiper-container shop-detail-slider ">
                                            <div class="swiper-wrapper">
                                                @if(count($product->convertPhotos()) > 0)

                                                    <div class="swiper-slide"><img class="img-fluid w-75"
                                                                                   src="{{$product->convertPhotos()[0]}}"
                                                                                   alt="..."></div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>

                        <div class="col-md-7">
                            <h2>{{$product->getTranslation('name')}}</h2>
                            {{--                <p class="text-muted">T-SHIRTS</p>--}}
                            <div>

                                <h2 class="pt-4 text-danger">
                                    <label class="h5 font-weight-bold text-dark">{{translate('Price')}} : </label>
                                    {{--                      {{(double) product_discounted_highest_price($product)}} {{$settings['general_settings']['currency']['code']}}--}}

                                    <div class="product-price d-inline">
                        <span
                            @if($product->discount > 0)
                                @if(is_product_dscounted($product))
                                    class="old" style="color:red;"
                        > {{(double) product_base_price($product)}} {{$settings['general_settings']['currency']['code']}}
                            @endif
                            @endif
                        </span>

                                    </div>
                                    {{(double) product_discounted_base_price($product)}} {{$settings['general_settings']['currency']['code']}}

                                </h2>
                            </div>
                            <hr>
                            <div class="mt-2 row w-100 align-items-end">
                                <label class="h5 col-md-3 font-weight-bold text-dark" for="">{{translate('Attributes')}}
                                    : </label>
                                <div class="col-md-9">
                                    @foreach($product->variations as $variation)
                                        {{--                            <div class="col-md-3">{{$variation_combinations->attribute->getTranslation('name')}}</div>--}}
                                        <label class="select_variant_btn text-light m-2 px-2">
                                            <input type="radio" name="product_variation_id" value="{{ $variation->id }}"
                                                   class="variation-radio">
                                            @foreach($variation->combinations as $combination)
                                                {{ $combination->attribute_value?->getTranslation('name') }}
                                            @endforeach
                                        </label>

                                    @endforeach

                                </div>
                            </div>
                            <hr>

                            <div class="row">
                                <div class="col-md-6 my-4">
                                    <label class="h5 font-weight-bold text-dark"
                                           for="quantityInput">{{translate('Quantity')}}
                                        : </label>
                                    <input type="number"
                                           min="{{ $product->min_qty }}"
                                           @if($product->max_qty > 0) max="{{ $product->max_qty }}" @endif
                                           class="col-md-3 border border-danger  text-center mx-3 form-control d-inline"
                                           value="1"
                                           id="quantityInput">
                                </div>
                            </div><!-- row -->
                            <div id="purchase-btn">
                                {{--
                                                    <button type="button" class="btn btn-sm btn-primary"><a class="text-white" href=""> Buy Now </a>
                                                    </button>
                                --}}
                                <a class="btn text-light btn-sm btn-light rounded" href="#" data-toggle="modal"
                                   data-target="#bulkPurchasingModal">
                                    <i class="fa fa-money pr-2"></i>
                                    {{translate('Bulk Purchasing')}} </a>
                                <button type="button" class="btn btn-sm btn-light rounded" onclick="addItemToCart()">
                                    <a class="text-black">
                                        <i class="fa fa-shopping-cart pr-2"></i> {{translate('Add to Cart')}} </a>
                                </button>
                            </div>
                        </div>


                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
<!-- Modal end -->
