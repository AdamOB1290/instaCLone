@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header text-center text-6xl login_brand text-white-100 pb-0 border-bottom-0">Instaclone</div>

                <div class="card-body pt-1">
                    <button class="btn btn-primary w-100 py-1" style="font-size: 15px;"><i class="fab fa-facebook-square mr-1 "></i> Continue with Facebook</button>
                    <div class="Or_wrapper position-relative d-flex justify-content-center mt-3">
                        <hr class=" login_hr w-100 ">
                        <span class="text-sm position-absolute p-2 bg-white login_or">OR</span> 
                    </div>
                    
                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        <div class="form-group row">

                            <div class="col-md-6">
                                <input id="email" type="email" placeholder="E-Mail Address" class="login_form form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>

                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">

                            <div class="col-md-6">
                                <input id="password" type="password" placeholder="Password" class="login_form form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">

                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-md-6 offset-md-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>

                                    <label class="form-check-label" for="remember">
                                        {{ __('Remember Me') }}
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-8 offset-md-4">
                                <button type="submit" class="btn btn-primary w-100 pt-0 pb-1" style="font-size: 15px;">
                                    {{ __('Login') }}
                                </button>
                                
                                <div class="d-flex flex-column align-items-center " >
                                    @if (Route::has('password.request'))
                                        <a class="text-blue-600 font-semibold  py-2 text-sm hover:no-underline hover:text-blue-700" href="{{ route('password.request') }}">
                                            {{ __('Forgot Your Password?') }}
                                        </a>
                                    @endif
                                    <span>Don't have an account ?<a href="{{ route('register') }}" class="text-blue-600 text-center font-semibold hover:no-underline hover:text-blue-800"> Sign up </a></span>
                                    
                                    <div class="Or_wrapper position-relative d-flex justify-content-center mt-3 w-100">
                                        <hr class=" login_hr w-100 ">
                                        <span class="text-sm position-absolute p-2 bg-white login_getApp">Get the app</span>
                                    </div>
                                    <div class="d-flex pt-3">
                                       <div class="w-50 mr-1">
                                            <a href="https://itunes.apple.com/app/instagram/id389801252?pt=428156&ct=igweb.loginPage.badge&mt=8&vt=lo"><img src="https://www.instagram.com/static/images/appstore-install-badges/badge_ios_english-en.png/180ae7a0bcf7.png" alt=""></a>
                                        </div>
                                        <div class="w-50 ml-1">
                                            <a href="https://play.google.com/store/apps/details?id=com.instagram.android&referrer=utm_source%3Dinstagramweb%26utm_campaign%3DloginPage%26ig_mid%3D20E3A03F-679A-42C6-A221-E7091B11A9C6%26utm_content%3Dlo%26utm_medium%3Dbadge">
                                                <img src="https://www.instagram.com/static/images/appstore-install-badges/badge_android_english-en.png/e9cd846dc748.png" alt="">
                                            </a>
                                        </div> 
                                    </div>
                                    

                                </div>
                            </div>
                            
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
<script>

</script>