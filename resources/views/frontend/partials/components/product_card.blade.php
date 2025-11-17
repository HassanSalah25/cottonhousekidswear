<div class="single-product">
    <div class="product-img">
        <a href="{{route('product.details.show',$product->slug)}}">
            {{--                                                         TODO Add Two photos for each product--}}
            <img class="default-img" src="{{api_asset($product->thumbnail_img)}}" alt="#">
            <img class="hover-img"
                 src="{{$product->thumbnail_img_hover ? api_asset($product->thumbnail_img_hover) : api_asset($product->thumbnail_img)}}"
                 alt="#">
            @if($product->discount > 0)
                @if(is_product_dscounted($product))
                    <span class="price-dec">{{$product->discount}}% {{translate('Off')}}</span>
                @endif
            @elseif($product->created_at > \Carbon\Carbon::now()->subDays(30))
                <span class="price-dec">{{translate('New')}}</span>
            @endif
        </a>
        <div class="button-head">
            <div class="product-action">
                <a data-toggle="modal" data-target="#modal-{{$product->id}}" title="Quick View" href="#"><i
                        class=" ti-eye"></i><span>{{translate('Quick Shop')}}</span></a>
                @auth
                    @if(in_array($product->id,auth()->user()?->wishlists()->pluck('product_id')->toArray()))
                        <a title="Wishlist" onclick="deleteItemFromWishlist(this,{{$product->id}})"
                           style="color: #ec1a25"><i class="ti-heart"></i><span>{{translate('Add to Wishlist')}}</span></a>
                    @else
                        <a title="Wishlist" onclick="addItemToWishlist(this,{{$product->id}})">
                            <i class="ti-heart"></i><span>{{translate('Add to Wishlist')}}</span></a>
                    @endif
                @else
                    <a title="Wishlist" href="{{route('customer.login')}}">
                        <i class="ti-heart"></i><span>{{translate('Add to Wishlist')}}</span>
                    </a>
                @endauth

                {{--                                                            <a title="Compare" href="#"><i class="ti-bar-chart-alt"></i><span>Add to Compare</span></a>--}}
            </div>
            <div class="product-action-2">
                @if($product->variations->count() == 1)
                    <a title="Add to Cart"
                       onclick="addItemToCart({{$product->variations->first()->id}})">{{translate('Add to Cart')}}</a>
                @else
                    <a title="Add to Cart"
                       href="{{ route('product.details.show',$product->slug) }}">{{translate('Add to Cart')}}</a>
                @endif
            </div>
        </div>
    </div>
    <div class="product-content">
        <h3><a href="{{route('product.details.show',$product->slug)}}">{{$product->getTranslation('name')}}</a></h3>
        <div class="product-price">
            <span
                @if($product->discount > 0)
                    @if(is_product_dscounted($product))
                        class="old" style="color:red;"
               @endif
                @endif
            > {{(double) product_base_price($product)}} {{$settings['general_settings']['currency']['code']}} </span>
            @if($product->discount > 0)
                @if(is_product_dscounted($product))

                    <span>{{(double) product_discounted_base_price($product)}} {{$settings['general_settings']['currency']['code']}}</span>
                @endif
            @endif
        </div>

    </div>
</div>
@push('modal')
    @include('frontend.partials.components.product-modal', ['product' => $product])
@endpush

