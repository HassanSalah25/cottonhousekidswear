<!-- Slider Area -->
<section id="carouselExampleControls" class="carousel slide" data-ride="carousel">
    <div class="hero-slider carousel-inner">
        <!-- Single Slider -->
        @foreach($sliders as $key => $slider)
            <a  href="{{$slider[0]['link']}}" class="single-slider carousel-item @if($key == 'one') active @endif"
            style="background-image: url({{$slider[0]['img']}});"
            >
                <div class="container">
                    <div class="row no-gutters ">
                        <div class="col-lg-9 offset-lg-3 col-12 ">
                            <div class="text-inner">
                                <div class="row">
                                    <div class="col-lg-7 col-12">
                                        <div class="hero-text">
                                            {!! translate($slider[0]['content']) !!}
{{--                                            <div class="button">--}}
{{--                                                <a href="{{$slider[0]['link']}}" class="btn">{{translate('Browse')}}</a>--}}
{{--                                            </div>--}}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        @endforeach
        <a class="carousel-control-prev" href="#carouselExampleControls" role="button"
           data-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="sr-only">Previous</span>
        </a>
        <a class="carousel-control-next" href="#carouselExampleControls" role="button"
           data-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="sr-only">Next</span>
        </a>
        <!--/ End Single Slider -->
    </div>
</section>
<!--/ End Slider Area -->
