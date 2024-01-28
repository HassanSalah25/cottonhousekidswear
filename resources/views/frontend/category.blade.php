@extends('frontend.layout.app')
@section('content')

    <div class="container-fluid mt-5 mb-5" id="productPage">
        <div class="row">
            <div class="col-md-3 border border-danger rounded m-3 p-3">
                <form action="{{route('page.category',$category->slug)}}" class="container">
                    <h2 class="bottom-border w-50 border-danger ">{{translate('Filters')}}</h2>
                    <div class="row">
                        <div class="col-md-12">
                            <h5 class="m-3">{{translate('Categories')}}: </h5>
                            <div class="row">
                                <select name="category_id">
                                    <option value="">{{translate('All')}}</option>
                                    @foreach($filter_categories as $category)
                                        <option value="{{$category->id}}"
                                            {{ $category->id == request('category_id') ? 'selected' : '' }}
                                        >{{$category->getTranslation('name')}}</option>
                                    @endforeach
                                </select>
                            </div>

                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <h5 class="m-3">{{translate('Price')}}: </h5>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="row align-items-center">
                                        <input type="range" class="form-range" id="range1" min="0" max="10000"
                                               step="1000" value="{{request('price_from')}}">

                                        <div class="col-md-6">
                                            <input type="number" name="price_from" class="form-control text-center" id="value1"
                                                   placeholder="{{translate('From')}}" value="{{request('price_from')}}">
                                        </div>

                                    </div>
                                    <div class="row align-items-center">
                                        <input type="range" class="form-range d-inline" id="range2" min="0" max="10000"
                                               step="1000" value="{{request('price_to')}}">

                                        <div class="col-md-6">
                                            <input type="number" name="price_to" class="form-control d-inline text-center" id="value2"
                                                   placeholder="{{translate('To')}}" value="{{request('price_to')}}">
                                        </div>

                                    </div>
                                </div>

                            </div>

                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <h5 class="m-3">{{translate('Sort By')}}: </h5>
                            <div class="row">
                                <select name="sort_by">
                                    <option value="latest"
                                        {{ "latest" == request('latest') ? 'selected' : '' }}
                                    >{{translate('latest')}}</option>
                                    <option value="oldest"
                                        {{ "oldest" == request('oldest') ? 'selected' : '' }}
                                    >{{translate('oldest')}}</option>
                                    <option value="highest_price"
                                        {{ "highest_price" == request('highest_price') ? 'selected' : '' }}
                                    >{{translate('highest_price')}}</option>
                                    <option value="lowest_price"
                                        {{ "lowest_price" == request('lowest_price') ? 'selected' : '' }}
                                    >{{translate('lowest_price')}}</option>
                                    <option value="num_of_sale"
                                        {{ "num_of_sale" == request('num_of_sale') ? 'selected' : '' }}
                                    >{{translate('num_of_sale')}}</option>
                                </select>
                            </div>

                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <h5 class="m-3">{{translate('Attributes')}}: </h5>
                            <div class="row">
                                @foreach($filter_attributes as $attribute)
                                    <div class="col-md-6 w-100 ">
                                        <input type="checkbox" class="mx-1" name="attribute_value_ids[]"
                                               value="{{$attribute->id}}"

                                            {{ in_array($attribute->id,request('attribute_value_ids')??[]) ? 'checked' : '' }}
                                        >{{$attribute->getTranslation('name')}}
                                    </div>
                                @endforeach
                            </div>

                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 my-3">
                            <button type="submit" class="btn btn-light btn-block">{{translate('Apply')}}</button>
                        </div>
                        <div class="col-md-6 my-3">
                            <button type="button" class="btn btn-reset btn-block">

                                <a href="{{route('page.all_products')}}" class="">{{translate('Reset')}}</a>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
            <div class="col-md-8 row">
                @if($all_products->count() > 0)
                    @foreach($all_products as $product)
                        <div class="col-xl-3 col-lg-4 col-md-4 col-12">
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
