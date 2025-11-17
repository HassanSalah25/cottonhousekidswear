@extends('frontend.layout.app')
@push('styles')
    <!-- Swiper slider-->
    <link rel="stylesheet" href="{{asset('assets/vendor/swiper/swiper-bundle.min.css')}}">
    <style>
        .swiper-container {
            border: 1px solid #eee;
        }

        .swiper-pagination-bars .swiper-pagination-bullet {
            width: 3.5rem;
            height: 4px;
            border-radius: 0;
            margin: 0 !important;
            background: #ccc;
        }

        .swiper-pagination-bars .swiper-pagination-bullet-active {
            background: #4fbfa8;
        }

        .swiper-pagination-light .swiper-pagination-bullet {
            background: #dadada;
            opacity: 1;
        }

        .swiper-pagination-light .swiper-pagination-bullet-active {
            background: #ffffff;
        }

        .swiper-pagination-bullet {
            width: 12px;
            height: 12px;
        }

        .swiper-pagination-bullet-active {
            background: #4fbfa8;
        }

        .swiper-button-next,
        .swiper-button-prev {
            top: 0;
            width: 3rem;
        }

        .swiper-button-next::after,
        .swiper-button-prev::after {
            font-size: 1.7rem;
            font-weight: bold;
            color: #4fbfa8;
        }

        .swiper-button-next.swiper-button-disabled,
        .swiper-button-prev.swiper-button-disabled {
            opacity: 1;
        }

        .swiper-button-next.swiper-button-disabled::after,
        .swiper-button-prev.swiper-button-disabled::after {
            color: #ccc;
        }

        .swiper-button-prev {
            left: 0;
        }

        .swiper-button-next {
            right: 0;
        }

        .swiper-slide-thumb {
            cursor: pointer;
            border: 5px solid transparent;
            transition: all 0.3s;
            opacity: 0.5;
        }

        .swiper-slide-thumb-active {
            border-color: rgba(79, 191, 168, 0.5);
            opacity: 1;
        }
        .table{
            width: 100% !important;
        }
    </style>
@endpush
@section('content')

    <div class="container mt-5 mb-5" id="productPage">
        <div class="row">
            <div class="col-md-5">
                <div class="ecommerce-gallery" data-mdb-zoom-effect="true" data-mdb-auto-height="true">
                    <div class="row py-3 shadow-5">
                        <div class="col-md-12 mb-1">
                            <div class="swiper-container shop-detail-slider ">
                                <div class="swiper-wrapper">
                                    @foreach($product->convertPhotos() as $img)
                                        <div class="swiper-slide"><img class="img-fluid" src="{{$img}}" alt="..."></div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        <div class="col-12 mb-1">
                            <div class="swiper-container shop-detail-slider-thumbs w-100">
                                <div class="swiper-wrapper">
                                    @if(count($product->convertPhotos()) > 1)
                                    @foreach($product->convertPhotos() as $img)
                                        <div class="swiper-slide swiper-slide-thumb flex-fill">
                                            <img class="img-fluid"
                                                 src="{{$img}}"
                                                 alt="..."></div>
                                    @endforeach
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
                    <h6 style="color: red">{{translate(get_setting('shipping_note'))}} </h6>
                </div>
                <div class="description text-muted mb-4">
                    {!! $product->getTranslation('description') !!}
                </div>
                <hr>
                <div class="mt-2 row w-100 align-items-end">
                    <label class="h5 col-md-3 font-weight-bold text-dark" for="">{{translate('Attributes')}} : </label>
                    <div class="col-md-9">
                        @foreach($product->variations as $variation)
                            {{--                            <div class="col-md-3">{{$variation_combinations->attribute->getTranslation('name')}}</div>--}}
                            <label class="select_variant_btn text-light m-2 px-2">
                                <input type="radio" name="product_variation_id" value="{{ $variation->id }}"
                                       class="variation-radio">
                                @foreach($variation->combinations as $combination)
                                    {{ $combination->attribute_value->getTranslation('name') }}
                                @endforeach
                            </label>

                        @endforeach

                    </div>
                </div>
                <hr>

                <div class="row">
                    <div class="col-md-6 my-4">
                        <label class="h5 font-weight-bold text-dark" for="quantityInput">{{translate('Quantity')}}
                            : </label>
                        <input type="number"
                               min="{{ $product->min_qty }}"
                               @if($product->max_qty > 0) max="{{ $product->max_qty }}" @endif
                               class=" col-md-3 border border-danger  text-center mx-3 form-control d-inline" value="1"
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
                            <i class="fa fa-shopping-cart pr-2"></i> {{translate('Add to Cart')}} </a></button>
                </div>
            </div>
            <div class="container">
                {{--<ul class="nav nav-tabs mt-5" id="myTab" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab"
                           aria-controls="home" aria-selected="true">{{translate('DESCRIPTION')}}</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="profile-tab" data-toggle="tab" href="#profile" role="tab"
                           aria-controls="profile" aria-selected="false">{{translate('INFORMATION')}}</a>
                    </li>
                   --}}{{-- <li class="nav-item">
                        <a class="nav-link" id="contact-tab" data-toggle="tab" href="#contact" role="tab"
                           aria-controls="contact" aria-selected="false">{{translate('DESCRIPTION')}}REVIEWS (1)</a>
                    </li>--}}{{--
                </ul>
                <div class="tab-content w-100 pt-5" id="myTabContent">
                    <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                        <h5>Product Description</h5>
                        <p class="text-muted">T-SHIRTS</p>
                        <div class="ratings">
                            <i class="fas fa-star text-primary"></i>
                            <i class="fas fa-star text-primary"></i>
                            <i class="fas fa-star text-primary"></i>
                            <i class="fas fa-star text-primary"></i>
                            <i class="far fa-star text-primary"></i>

                        </div>
                        <h5 class="pt-4">$179.00</h5>
                        <p class="description text-muted">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed
                            do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis
                            nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute
                            irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur.
                            Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit
                            anim id est laborum.

                            Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt
                            ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco
                            laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in
                            voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat
                            cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>

                    </div>
                    <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                        <h5>Additional Information</h5>
                        <br>
                        <table class="table table-striped table-bordered">
                            <thead>
                            <tr>
                                <th scope="row">Weight</th>
                                <td><i>0.3 kg</i></td>
                            </tr>
                            </thead>

                            <tbody>

                            <tr>
                                <th scope="row">Dimensions</th>
                                <td><i>50 × 60 cm</i></td>
                            </tr>

                            </tbody>
                        </table>
                    </div>
                    <div class="tab-pane fade" id="contact" role="tabpanel" aria-labelledby="contact-tab">
                        <div class="review-rating float-right mt-5">
                            <i class="fas fa-star text-primary"></i>
                            <i class="fas fa-star text-primary"></i>
                            <i class="fas fa-star text-primary"></i>
                            <i class="fas fa-star text-primary"></i>
                            <i class="far fa-star text-primary"></i>

                        </div>
                        <h5>1 review for Fantasy T-shirt</h5>
                        <br>
                        <div class="row">
                            <div class="col-md-6 mt-2">
                                <div class="row">
                                    <div class="col-md-2">
                                        <img src="https://mdbootstrap.com/img/Photos/Others/placeholder1.jpg"
                                             alt="IMAGE LOADING" class="img-fluid">
                                    </div>
                                    <div class="col-md-10">
                                        <p class="mb-1"><strong> Marthasteward </strong> – Jan 28, 2020 </p>
                                        <p>Nice one, love it!</p>
                                    </div>
                                </div>
                            </div>

                        </div>
                        <hr>

                        <h5 class="mt-3 pt-2">Add a Review</h5>
                        <p>Your email address will not be published.</p>
                        <div class="ratings">
                            <i class="fas fa-star text-primary"></i>
                            <i class="fas fa-star text-primary"></i>
                            <i class="fas fa-star text-primary"></i>
                            <i class="fas fa-star text-primary"></i>
                            <i class="far fa-star text-primary"></i>

                        </div>
                        <form class="mt-3" id="addreview">
                            <div class="form-group text-muted">
                                <textarea class="form-control" id="exampleFormControlTextarea1" rows="4"
                                          placeholder="Your Review"></textarea>
                            </div>
                            <div class="form-group text-muted">
                                <input type="text" class="form-control" placeholder="Name">
                            </div>
                            <div class="form-group text-muted">
                                <input type="email" class="form-control" id="exampleInputEmail1"
                                       aria-describedby="emailHelp" placeholder="Email">
                            </div>

                            <div class="text-right">
                                <button type="submit" class="btn btn-primary">Add a Review</button>
                            </div>
                        </form>

                    </div>
                </div>--}}
                <br><br>
                <hr>
                <br>
                @include('frontend.partials.sections.popular')
                <br>
                <br>
                <br>
            </div>


        </div>
    </div>

    @if($errors)
        @foreach($errors->getMessageBag()->all() as $key => $message)
            <div class="d-flex justify-content-center" id="alerting">
                <div class="alert alert-danger">{{ $message }}</div>
            </div>
        @endforeach
    @endif

    <!-- Modal -->
    <div class="modal fade" id="bulkPurchasingModal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span class="ti-close"
                                                                                                      aria-hidden="true"></span>
                    </button>
                </div>
                <div class="modal-body h-auto d-flex justify-content-center align-items-center">

                    <form class="container-fluid no-gutters d-flex flex-nowrap justify-content-between flex-column"
                          method="POST" action="{{route('checkout.bulks.store')}}">
                        @csrf
                        <input type="hidden" name="product_id" value="{{$product->id}}">
                        <div class="my-2">
                            <label class="label">{{translate('Email Address')}}</label>
                            <input type="email" class="form-control"
                                   name="email"
                                   placeholder="{{translate('Enter Your Email Address')}}">

                        </div>
                        <div class="my-2">
                            <label class="label">{{translate('Phone')}}</label>
                            <input type="number" class="form-control"
                                   name="phone"
                                   placeholder="{{translate('Enter Your Phone')}}">
                        </div>
                        <div class="my-2">
                            <label class="label">{{translate('Quantity')}}</label>
                            <input type="number" class="form-control" name="quantity"
                                   placeholder="{{translate('Enter Your Quantity')}}">
                        </div>
                        <div class="my-2">
                            <label class="label">{{translate('Notes')}}</label>
                            <textarea type="number" class="form-control" name="notes"
                                      placeholder="{{translate('Enter Your Notes')}}"></textarea>
                        </div>
                        <div class="my-2">
                            <button type="submit" class="btn btn-primary">{{translate('Submit')}}</button>
                        </div>
                    </form>

                </div>
            </div>
        </div>

    </div>
@endsection
@push('scripts')
    <script src="{{asset('assets/vendor/swiper/swiper-bundle.min.js')}}"></script>
    <script>

        /* =====================================================
                PRODUCT DETAILS THUMBS SLIDER
            ===================================================== */
        var productDetailSliderThumb = new Swiper(".shop-detail-slider-thumbs", {
            spaceBetween: 10,
            slidesPerView: 4,
            freeMode: true,
            watchSlidesVisibility: true,
            watchSlidesProgress: true,
        });

        /* =====================================================
            PRODUCT DETAILS SLIDER
        ===================================================== */
        var productDetailSlider = new Swiper(".shop-detail-slider", {
            spaceBetween: 10,
            navigation: {
                nextEl: ".swiper-button-next",
                prevEl: ".swiper-button-prev",
            },
            thumbs: {
                swiper: productDetailSliderThumb,
            },
        });
    </script>

@endpush
