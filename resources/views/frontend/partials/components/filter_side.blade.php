<div class="mobile-side-bar">
    <a class="toggleButton">
        <i class="fa fa-solid fa-filter bg-danger p-2 text-light rounded mx-3"></i>
    </a>
</div>
<div class="col-md-3 border border-danger h-100 rounded m-3 p-3 side_bar">

    <form action="{{route('page.all_products')}}" class="container">
        <input type="hidden" name="keyword" value="{{ request('keyword') }}">
        <div class="mobile-side-bar">
            <a class="toggleButton">
                <li class="fa fa-close"></li>
            </a>
        </div>
        <div class="row justify-content-center">
            <h2 class="bottom-border w-50 border-danger text-center">{{translate('Filters')}}</h2>
        </div>

        <div class="row">
            <h5 class="my-3 text-danger ">{{translate('Categories')}}: </h5>
            <div class="col-md-12">
                <div class="row">
                    <select name="category_id">
                        <option value="">{{translate('All')}}</option>
                        @foreach($filter_categories as $category)
                            <option value="{{$category->id}}"
                                {{ ($category->id == request('category_id') || $category->slug == request()->segment(2) ) ? 'selected' : '' }}
                            >{{$category->getTranslation('name')}}</option>
                        @endforeach
                    </select>
                </div>

            </div>
        </div>
        <div class="row">
            <h5 class="my-0 text-danger ">{{translate('Price')}}: </h5>
            <div class="col-md-12">
                <div class="row">
                    <div class="col-md-12">
                        <div class="row align-items-center mb-2">
                            <input type="range" class="form-range" id="range1" min="0" max="10000"
                                   step="1000" value="{{request('price_from')}}">

                            <div class="col-md-6">
                                <input type="number" name="price_from" class="form-control text-center"
                                       id="value1"
                                       placeholder="{{translate('From')}}"
                                       value="{{request('price_from')}}">
                            </div>

                        </div>
                        <div class="row align-items-center mb-2">
                            <input type="range" class="form-range d-inline" id="range2" min="0" max="10000"
                                   step="1000" value="{{request('price_to')}}">

                            <div class="col-md-6">
                                <input type="number" name="price_to"
                                       class="form-control d-inline text-center" id="value2"
                                       placeholder="{{translate('To')}}" value="{{request('price_to')}}">
                            </div>

                        </div>
                    </div>

                </div>

            </div>
        </div>
        <div class="row">
            <h5 class="my-3 text-danger ">{{translate('Sort By')}}: </h5>
            <div class="col-md-12">
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
            <h5 class="my-3 text-danger ">{{translate('Attributes')}}: </h5>
            <div class="col-md-12 border">
                @foreach($filter_attributes as $attribute)
                    <h5 class="row m-1">{{$attribute->getTranslation('name')}}</h5>
                    <div class="row m-1 ">
                        @foreach($attribute->attribute_values as  $value)
                            <div class="col-md-6 w-100 ">
                                <input type="checkbox" class="mx-1" name="attribute_value_ids[]"
                                       value="{{$value->id}}"

                                    {{ in_array($value->id,request('attribute_value_ids')??[]) ? 'checked' : '' }}
                                >{{$value->getTranslation('name')}}
                            </div>
                        @endforeach
                    </div>
                @endforeach
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
