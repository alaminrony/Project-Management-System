@extends('layouts.login')

@section('login_content')
<!-- BEGIN LOGIN FORM -->
<form class="login-form" method="POST" action="{{ route('login') }}">
    @csrf
    <div class="row">
        <div class="col-md-12">
            <!-- BEGIN LOGO -->
            <div class="logo">
                <a href="#">
                    <img src="{{URL::to('/')}}/public/img/login_logo.png" alt="logo"/>
                </a>
            </div>
            <!-- END LOGO -->

        </div>
    </div>

    <div class="form-group">
        <!--ie8, ie9 does not support html5 placeholder, so we just show field title for that-->
        <label class="control-label visible-ie8 visible-ie9">@lang('label.USERNAME')</label>
        <input id="userName" type="text" class="form-control form-control-solid placeholder-no-fix {{ $errors->has('username') ? ' is-invalid' : '' }}" placeholder="Username" name="username" value="{{ old('username') }}" required>

        @if ($errors->has('username'))
        <span class="invalid-feedback">
            <strong class="text-danger">{{ $errors->first('username') }}</strong>
        </span>
        @endif
    </div>
    <div class="form-group">
        <label class="control-label visible-ie8 visible-ie9">Password</label>
        <input id="password" type="password" class="form-control form-control-solid placeholder-no-fix{{ $errors->has('password') ? ' is-invalid' : '' }}" placeholder="Password" name="password" required>

        @if ($errors->has('password'))
        <span class="invalid-feedback">
            <strong class="text-danger">{{ $errors->first('password') }}</strong>
        </span>
        @endif
    </div>

    <div class="form-actions">
        <button type="submit" class="btn green uppercase">Login</button>
        <!--label class="rememberme check mt-checkbox mt-checkbox-outline">
            <input type="checkbox" name="remember" value="1" />Remember
            <span></span>
        </label>
        <a href="{{ route('password.request') }}" id="forget-password" class="forget-password">Forgot Password?</a-->
    </div>
	{{-- <div class="copyright">@lang('label.COPYRIGHT') &copy; {{ date('Y') }}  @lang('label.STERLING_GROUP') | @lang('label.POWERED_BY_RAJAKINI'), @lang('label.A_PRODUCT_OF')
            <a target="_blank" href="http://www.swapnoloke.com/">@lang('label.SWAPNOLOKE')</a>
        </div> --}}
   {{-- <div class="login-options">
        <h4>Or login with</h4>
        <ul class="social-icons">
            <li>
                <a class="social-icon-color facebook" data-original-title="facebook" href="javascript:;"></a>
            </li>
            <li>
                <a class="social-icon-color twitter" data-original-title="Twitter" href="javascript:;"></a>
            </li>
            <li>
                <a class="social-icon-color googleplus" data-original-title="Goole Plus" href="javascript:;"></a>
            </li>
            <li>
                <a class="social-icon-color linkedin" data-original-title="Linkedin" href="javascript:;"></a>
            </li>
        </ul>
    </div --}}
</form>
<!-- END LOGIN FORM -->
@endsection
