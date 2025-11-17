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
    background-image: url({{$settings['banners']['forgot_page']['img']}});"></div>
            <div class="info-holder">

            </div>
        </div>
        <div class="form-holder">
            <div class="form-content">
                <div class="form-items">
                    <h3>{{translate('Password Reset')}}</h3>
                    <p>{{translate('To reset your password, enter the email address you use to sign in to iofrm')}}</p>
                    <form action="{{route('password.create')}}" method="POST">
                        @csrf
                        <input class="form-control" type="text" name="email" placeholder="{{translate('E-mail Address')}}" required>
                        <div class="form-button full-width">
                            <button id="submit" type="submit" class="ibtn ">{{translate('Send Code')}}</button>
                        </div>
                    </form>
                </div>
                <div class="form-sent">
                    <div class="tick-holder">
                        <div class="tick-icon"></div>
                    </div>
                    <h3>{{translate('Password link sent')}}</h3>
                    <p>{{translate('Please check your inbox')}}</p>
                    <div class="info-holder">
                        <span>{{translate('Unsure if that email address was correct?')}}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
