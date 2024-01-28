@extends('frontend.layout.app')
@section('content')

    @include('frontend.partials.sections.slider')

    @include('frontend.partials.sections.small_banner')

    @include('frontend.partials.sections.trending_product')

    @include('frontend.partials.sections.medium_banner')

    @include('frontend.partials.sections.popular')

    @include('frontend.partials.sections.best_seller')

    @include('frontend.partials.sections.newsettler')



    <!-- Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span class="ti-close"
                                                                                                      aria-hidden="true"></span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row no-gutters">
                        <div class="col-lg-6 offset-lg-3 col-12">
                            <h4 style="margin-top:100px;font-size:14px; font-weight:500; color:#F7941D; display:block; margin-bottom:5px;">
                                Eshop Free Lite</h4>
                            <h3 style="font-size:30px;color:#333;">Currently You are using free lite Version of Eshop.
                                <h3>
                                    <p style="display:block; margin-top:20px; color:#888; font-size:14px; font-weight:400;">
                                        Please, purchase full version of the template to get all pages, features and
                                        commercial license</p>
                                    <div class="button" style="margin-top:30px;">
                                        <a href="https://wpthemesgrid.com/downloads/eshop-ecommerce-html5-template/"
                                           target="_blank" class="btn" style="color:#fff;">Buy Now!</a>
                                    </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal end -->

@endsection
