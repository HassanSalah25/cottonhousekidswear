<!-- Start Shop Home List  -->
<section class="shop-home-list section">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12">
                <div class="shop-section-title">
                    <h1>{{translate('Best Seller')}}</h1>
                </div>
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-12 row justify-content-center">
                @foreach($best_selling_products as $product)
                    <!-- Start Single List  -->
                    <div class="single-list">
                        <div class="row">
                            <div class="col-lg-6 col-md-6 col-12">
                                <div class="list-image overlay">
                                    <img width="200" height="200" src="{{api_asset($product->thumbnail_img)}}" alt="#">
                                    <a href="{{route('product.details.show',$product->slug)}}" class="buy"><i class="fa fa-shopping-bag"></i></a>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-12 no-padding">
                                <div class="content">
                                    <h4 class="title"><a href="{{route('product.details.show',$product->slug)}}">{{$product->getTranslation('name')}}</a></h4>

                                    @if(is_product_dscounted($product))

                                        <p class="price with-discount old">
                                            {{(double) product_base_price($product)}} {{$settings['general_settings']['currency']['code']}}
                                        </p><br>

                                    @endif
                                    <p class="price with-discount ">
                                        {{(double) product_discounted_base_price($product)}} {{$settings['general_settings']['currency']['code']}}
                                    </p>

                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- End Single List  -->
                @endforeach

            </div>
        </div>
    </div>
</section>
<!-- End Shop Home List  -->
