@extends('frontend.layout.app')
@section('content')

    <div class="container-fluid mt-5 mb-5" id="productPage">
        <div class="row">
            @include('frontend.partials.components.filter_side')
            <div class="col-md-8 row">
                @if($all_products->count() > 0)
                    @foreach($all_products as $product)
                        <div class="col-xl-3 col-lg-4 col-md-4 col-6">
                            @include('frontend.partials.components.product_card')
                        </div>
                    @endforeach
                    {{$all_products->links()}}
                @else
                    <div class="col-md-12">
                        <h2 class="text-center">{{translate('No Products Found')}}</h2>
                    </div>
                @endif

            </div>
        </div>

    </div>

@endsection
