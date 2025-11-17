<!-- Start Footer Area -->
<footer class="footer">
    <!-- Footer Top -->
    <div class="footer-top section">
        <div class="container">
            <div class="row">
                <div class="col-lg-4 col-md-6 col-12">
                    <!-- Single Widget -->
                    <div class="single-footer about">
                        <div class="logo">
                            <a href="/"><img src="{{$settings['footer']['footer_logo']}}" alt="#"></a>
                        </div>
                        <p class="text">{!! get_setting('home_about_us') !!}</p>
                        <p class="call">{{translate('Got Question? Call us 24/7')}}</p>
                    </div>
                    <!-- End Single Widget -->
                </div>
                <div class="col-lg-2 col-md-6 col-12">
                    <!-- Single Widget -->
                    <div class="single-footer links">
                        <h4>{{translate($settings['footer']['footer_link_one']['title'])}}</h4>
                        <ul>
                            @foreach($settings['footer']['footer_link_one']['menu'] as $key => $link)
                                <li><a href="{{$link}}">{{translate($key)}}</a></li>
                            @endforeach
                        </ul>
                    </div>
                    <!-- End Single Widget -->
                </div>
                <div class="col-lg-2 col-md-6 col-12">
                    <!-- Single Widget -->
                    <div class="single-footer links">
                        <h4>{{translate($settings['footer']['footer_link_two']['title'])}}</h4>
                        <ul>
                            @foreach($settings['footer']['footer_link_two']['menu'] as $key => $link)
                                <li><a href="{{$link}}">{{translate($key)}}</a></li>
                            @endforeach
                        </ul>
                    </div>
                    <!-- End Single Widget -->
                </div>
                <div class="col-lg-4 col-md-6 col-12">
                    <!-- Single Widget -->
                    <div class="single-footer social">
                        <h4>{{translate('Get In Touch')}}</h4>
                        <!-- Single Widget -->
                        <div class="contact">
                            <ul>
                                <li><i class="ti-location-pin"></i> {{$settings['contact_info']['contact_address']}}
                                </li>
                                <li><i class="ti-location-pin"></i> {{$settings['contact_info']['contact_address_2']}}
                                </li>
                                <li><i class="fa fa-envelope"></i> {{$settings['contact_info']['contact_email']}}</li>
{{--                                @if(count(explode('-', $settings['contact_info']['contact_phone'])) > 1)--}}
{{--                                    @foreach(explode('-', $settings['contact_info']['contact_phone']) as $phone)--}}

{{--                                        <li>--}}
{{--                                            <a href="tel:{{trim($phone)}}">--}}
{{--                                            <i class="fa fa-phone"></i>--}}
{{--                                                {{trim($phone)}}--}}
{{--                                            </a>--}}
{{--                                        </li>--}}

{{--                                    @endforeach--}}

{{--                                @else--}}
{{--                                    <li><i class="fa fa-phone"></i> {{$settings['contact_info']['contact_phone']}}</li>--}}
{{--                                @endif--}}
                            </ul>
                        </div>
                        <!-- End Single Widget -->
                        <ul>
                            @foreach($settings['social_link'] as $key => $link)
                                @if($link)
                                    <li>
                                        <a href="{{$link}}">
                                            <i class="fab fa-{{$key}}"></i>
                                        </a>
                                    </li>
                                @endif
                            @endforeach
                        </ul>
                    </div>
                    <!-- End Single Widget -->
                </div>
            </div>
        </div>
    </div>
    <!-- End Footer Top -->
    <div class="copyright">
        <div class="container">
            <div class="inner">
                <div class="row justify-content-center">
                    <div class="col-md-6">
                        <div class="left text-center">
                            {!! translate($settings['footer']['copyright_text']) !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</footer>
<!-- /End Footer Area -->
