<!-- Start Midium Banner  -->
<section class="midium-banner">
    <div class="container">
        <div class="row">
            @foreach($home_banner_section_one as $section)
                <!-- Single Banner  -->
                <div class="col-lg-6 col-md-6 col-12">
                    <div class="single-banner">
                        <img src="{{$section['img']}}" alt="#">
                        <div class="content">
                            {!! $section['content'] !!}
                            <a href="{{$section['link']}}">{{translate('Browse')}}</a>
                        </div>
                    </div>
                </div>
                <!-- /End Single Banner  -->
            @endforeach
        </div>
    </div>
</section>
<!-- End Midium Banner -->
