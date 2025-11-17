@extends('frontend.layout.app')
@section('content')

    <div class="container-fluid mt-5 mb-5">
        <!-- Start Midium Banner  -->
        <section class="midium-banner">
            <div class="container">
                <div class="row">
                    @foreach($offers as $offer)
                        <!-- Single Banner  -->
                        <div class="col-lg-6 col-md-6 col-12 my-3">
                            <div class="single-banner">
                                <img src="{{api_asset($offer->banner)}}" alt="#">
                                <div class="content">
                                    <h2 class="p-3 rounded bg-danger"> {{$offer->title}} </h2>
                                    <a href="{{route('page.offer',['slug' =>$offer->slug])}}">{{translate('Browse')}}</a>
                                </div>
                            </div>
                        </div>
                        <!-- /End Single Banner  -->
                    @endforeach
                </div>
            </div>
        </section>
        <!-- End Midium Banner -->
    </div>

@endsection
