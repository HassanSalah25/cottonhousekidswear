@extends('frontend.layout.app')
@section('content')
    @include('frontend.partials.sections.slider')

{{--    @include('frontend.partials.sections.small_banner')--}}

    @include('frontend.partials.sections.trending_product')

    @include('frontend.partials.sections.medium_banner')

    @include('frontend.partials.sections.popular')

    @include('frontend.partials.sections.best_seller')

    @include('frontend.partials.sections.newsettler')
@endsection
