<!-- Start Product Area -->
<div class="product-area section">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="section-title">
                    <h2>{{translate('Trending Item')}}</h2>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="product-info">
                    <div class="nav-main">
                        <!-- Tab Nav -->
                        <ul class="nav nav-tabs" id="myTab" role="tablist">
                            @foreach($product_sections as $section_title)
                                <li class="nav-item"><a class="nav-link @if($loop->iteration == 1) active @endif"
                                                        data-toggle="tab" href="#{{$section_title['title']}}"
                                                        role="tab">{{translate($section_title['title'])}}</a>
                                </li>

                            @endforeach
                        </ul>
                        <!--/ End Tab Nav -->
                    </div>
                    <div class="tab-content" id="myTabContent">
                        <!-- Start Single Tab -->
                        @foreach($product_sections as $section_title)
                            <div class="tab-pane fade show @if($loop->iteration == 1) active @endif"
                                 id="{{$section_title['title']}}" role="tabpanel">
                                <div class="tab-single">
                                    <div class="row">
                                        @foreach($section_title['products'] as $product)
                                            <div class="col-xl-3 col-lg-4 col-md-4 col-6">
                                                @include('frontend.partials.components.product_card')
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        @endforeach

                        <!--/ End Single Tab -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- End Product Area -->
