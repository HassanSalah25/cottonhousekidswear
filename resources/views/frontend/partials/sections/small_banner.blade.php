<!-- Start Small Banner  -->
<section class="small-banner section">
    <div class="container-fluid">
        <div class="row">
            @foreach($popular_categories as $category)
                <!-- Single Banner  -->
                <a href="{{route('page.category',$category->slug)}}" class="col-lg-4 col-md-6 col-12">
                    <div class="single-banner">
                        <img src="{{api_asset($category->banner)}}" alt="#">
                        <div class="content">
{{--                            <h2 style="color: red;background-color: #ffffff;padding: 12px">{{$category->getTranslation('name')}}</h2>--}}
{{--                            <a style="background-color: #333 ;" href="{{route('page.category',$category->slug)}}">{{translate('Discover Now')}}</a>--}}
                        </div>
                    </div>
                </a>
                <!-- /End Single Banner  -->
            @endforeach

        </div>
    </div>
</section>
<!-- End Small Banner -->
