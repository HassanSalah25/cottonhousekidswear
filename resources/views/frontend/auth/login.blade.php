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
    background-image: url({{$settings['banners']['login_page']['img']}});"></div>
                <div class="info-holder">

                </div>
            </div>
            <div class="form-holder">
                <div class="form-content">
                    <div class="form-items">
                        <h3>{{translate('Get more things done with Loggin platform.')}}</h3>
                        <p>{{translate('Access to the most powerfull tool in the entire design and web industry.')}}</p>
                        <div class="page-links">
                            <a href="" class="active">{{translate('Login')}}</a><a href="{{route('signup')}}">{{translate('Register')}}</a>
                        </div>
                        <form action="{{route('loginSubmit')}}" method="POST">
                            @csrf
                            <input class="form-control" type="text" name="email" placeholder="{{translate('E-mail Address')}}" required>
                            <input class="form-control" type="password" name="password" placeholder="{{translate('Password')}}" required>
{{--                            <input type="checkbox" name="remember" id="chk1"><label for="chk1">{{translate('Remmeber me')}}</label>--}}
                            <div class="form-button">
                                <button id="submit" type="submit" class="ibtn">{{translate('Login')}}</button> <a href="{{route('password.create_form')}}">{{translate('Forget password?')}}</a>
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

