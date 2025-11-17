@extends('backend.layouts.blank')

@section('content')

    <div class="form-body without-side">

        <div class="row">

            <div class="form-holder">
                <div class="form-content">
                    <div class="form-items">
                        <div class="form-icon">
                            <div class="icon-holder">
                                <img class="logo-size" src="{{static_asset('assets/img/logo.png')}}" alt="">
                            </div>
                        </div>
                        <div class="mb-4 text-center">
                            <h1 class="h3 text-primary mb-0 border-top text-uppercase pt-3" style="border-color: #fff !important">{{ translate('Welcome') }}</h1>
                            <p class="fs-15 opacity-80 text-center">{{ translate('Login to your account.') }}</p>
                        </div>
                        <form class="pad-hor" method="POST" role="form" action="{{ route('login') }}">
                            @csrf
                            <div class="form-group">
                                <label class="mb-1">{{ translate('Email') }}</label>
                                <input id="email" type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ old('email') }}" required autofocus placeholder="{{ translate('Email') }}">
                                @if ($errors->has('email'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                            </div>
                            <div class="form-group">
                                <label class="mb-1">{{ translate('Password') }}</label>
                                <input id="password" type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" required placeholder="{{ translate('Password') }}">
                                @if ($errors->has('password'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
                            </div>
                            <div class="row mb-2">
                                <div class="col-sm-6">
                                    <div class="text-left">
                                        <label class="aiz-checkbox">
                                            <input type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                                            <span>{{ translate('Remember Me') }}</span>
                                            <span class="aiz-square-check bg-white"></span>
                                        </label>
                                    </div>
                                </div>
                                @if(env('MAIL_USERNAME') != null && env('MAIL_PASSWORD') != null)
                                    <div class="col-sm-6">
                                        <div class="text-right">
                                            <a href="{{ route('password.request') }}" class="text-reset fs-14">{{translate('Forgot password')}} ?</a>

                                        </div>
                                    </div>
                                @endif
                            </div>
                            <button type="submit" class="btn btn-primary btn-lg btn-block">
                                {{ translate('Login') }}
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>


@endsection

@section('script')
<script type="text/javascript">
    function autoFill(){
        $('#email').val('admin@example.com');
        $('#password').val('123456');
    }
    function autoFillSeller(){
        $('#email').val('seller@example.com');
        $('#password').val('123456');
    }
</script>
@endsection
