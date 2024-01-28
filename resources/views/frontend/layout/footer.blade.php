<!-- Start Footer Area -->
<footer class="footer">
    <!-- Footer Top -->
    <div class="footer-top section">
        <div class="container">
            <div class="row">
                <div class="col-lg-5 col-md-6 col-12">
                    <!-- Single Widget -->
                    <div class="single-footer about">
                        <div class="logo">
                            <a href="/"><img src="{{$settings['footer']['footer_logo']}}" alt="#"></a>
                        </div>
                        <p class="text"></p>
                        <p class="call">{{translate('Got Question? Call us 24/7')}}<span><a href="tel:{{$settings['contact_info']['contact_phone']}}">{{$settings['contact_info']['contact_phone']}}</a></span></p>
                    </div>
                    <!-- End Single Widget -->
                </div>
                <div class="col-lg-2 col-md-6 col-12">
                    <!-- Single Widget -->
                    <div class="single-footer links">
                        <h4>{{$settings['footer']['footer_link_one']['title']}}</h4>
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
                        <h4>{{$settings['footer']['footer_link_two']['title']}}</h4>
                        <ul>
                            @foreach($settings['footer']['footer_link_two']['menu'] as $key => $link)
                                <li><a href="{{$link}}">{{translate($key)}}</a></li>
                            @endforeach
                        </ul>
                    </div>
                    <!-- End Single Widget -->
                </div>
                <div class="col-lg-3 col-md-6 col-12">
                    <!-- Single Widget -->
                    <div class="single-footer social">
                        <h4>{{translate('Get In Tuoch')}}</h4>
                        <!-- Single Widget -->
                        <div class="contact">
                            <ul>
                                @foreach($settings['contact_info'] as $key => $link)
                                    <li>{{translate($link)}}</li>
                                @endforeach
                            </ul>
                        </div>
                        <!-- End Single Widget -->
                        <ul>
                            @foreach($settings['social_link'] as $key => $link)

                            <li>
                                <a href="{{$link}}">
                                    <i class="fa fa-{{$key}}"></i>
                                </a>
                            </li>
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
                            {!! $settings['footer']['copyright_text'] !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</footer>
<!-- /End Footer Area -->
