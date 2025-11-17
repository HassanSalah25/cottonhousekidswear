@extends('frontend.auth.layout.app')
@section('content')
    <div class="form-body">
        <div class="website-logo">
            <a href="index.html">
                <div class="logo">
                    <img class="logo-size" src="{{$settings['appLogo']}}" alt="">
                </div>
            </a>
        </div>
        <div class="row">
            <div class="img-holder">
                <div class="bg" style="
    background-image: url({{$settings['banners']['registration_page']['img']}});"></div>
                <div class="info-holder">

                </div>
            </div>
            <div class="form-holder">
                <div class="form-content">
                    <div class="form-items">
                        <h3>{{translate('Get more things done with Loggin platform.')}}</h3>
                        <p>{{translate('Access to the most powerfull tool in the entire design and web industry.')}}</p>
                        <div class="page-links">
                            <a href="{{route('customer.login')}}">{{translate('Login')}}</a><a href="{{route('signup')}}" class="active">{{translate('Register')}}</a>
                        </div>
                        <form method="POST" action="{{route('signupSubmit')}}" enctype="multipart/form-data">
                            @csrf
                            <input class="form-control" type="text" name="name" placeholder="{{translate('Full Name')}}" >
                            <input class="form-control" type="email" name="email" placeholder="{{translate('E-mail Address')}}" >
                            <input class="form-control" type="password" name="password" placeholder="{{translate('Password')}}" >
                            <div class="form-button">
                                <button id="submit" type="submit" class="ibtn">{{translate('Register')}}</button>
                            </div>
                        </form>
                        <div class="other-links">
                            <span>{{translate('Or login with')}}</span><a href="{{route('social.login','facebook')}}"><i class="fa fa-facebook-f"></i></a><a href="{{route('social.login','google')}}"><i class="fa fa-google"></i></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@prepend('styles')
<link rel="stylesheet" href="{{ asset('frontend/css/auth.css') }}" />
@endprepend
