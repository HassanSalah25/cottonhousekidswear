<!-- Start Small Banner  -->
<section class="small-banner section">
    <div class="container-fluid">
        <div class="row">
            @foreach($popular_categories as $category)
                <!-- Single Banner  -->
                <div class="col-lg-4 col-md-6 col-12">
                    <div class="single-banner">
                        <img src="{{api_asset($category->banner)}}" alt="#">
                        <div class="content">
                            <p>{{$category->getTranslation('name')}}</p>
                            <a href="#">{{translate('Discover Now')}}</a>
                        </div>
                    </div>
                </div>
                <!-- /End Single Banner  -->
            @endforeach

        </div>
    </div>
</section>
<!-- End Small Banner -->
