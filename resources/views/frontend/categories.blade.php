@extends('frontend.layout.app')
@section('content')

    <div class="container-fluid mt-5 mb-5">
        <!-- Start Midium Banner  -->
        <section class="midium-banner">
            <div class="container">
                <div class="row">
                    @foreach($all_categories as $category)
                        <!-- Single Banner  -->
                        <a href="{{route('page.all_sub_category',$category->slug)}}" class="col-lg-6 col-md-6 col-12 my-3">
                            <div class="single-banner">
                                <img src="{{api_asset($category->banner)}}" alt="#">
                                <div class="content">
{{--                                    <h2 class="text-danger"> {{$category->getTranslation('name')}} </h2>--}}
{{--                                    <a href="{{route('page.category',$category->slug)}}">{{translate('Browse')}}</a>--}}
                                </div>
                            </div>
                        </a>
                        <!-- /End Single Banner  -->
                    @endforeach
                </div>
            </div>
        </section>
        <!-- End Midium Banner -->
    </div>

@endsection
