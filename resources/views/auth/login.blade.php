@extends('layouts.app')

@section('content')
<div class=" login_wrapper p-0 h-full flex justify-around items-center  mx-auto">
    <div class="login_bg_img hidden md:inline-block">
        <div class="img-container" data-slideshow>
            <img src="https://www.instagram.com/static/images/homepage/screenshot1-2x.jpg/9144d6673849.jpg">
            <img src="https://www.instagram.com/static/images/homepage/screenshot2-2x.jpg/177140221987.jpg">
            <img src="https://www.instagram.com/static/images/homepage/screenshot3-2x.jpg/ff2c097a681e.jpg">
            <img src="https://www.instagram.com/static/images/homepage/screenshot4-2x.jpg/b27a108592d8.jpg">
            <img src="https://www.instagram.com/static/images/homepage/screenshot5-2x.jpg/5e04169b9308.jpg">
          </div>
    </div>
    <div class="max-w-xs">
        <div class="card ">
            <div class="card-header text-center text-6xl login_brand text-white-100 pb-0 border-bottom-0">Instaclone</div>

            <div class="card-body pt-1 px-0 flex items-center flex-col">
                
                <form method="POST" class="w-11/12" action="{{ route('login') }}">
                    @csrf

                    <div class="form-group ">

                        <div class="">
                            <input id="email" type="email" placeholder="E-Mail Address" class="login_form form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>

                            @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group ">

                        <div class="">
                            <input id="password" type="password" placeholder="Password" class="login_form form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">

                            @error('password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group ">
                            <div class="form-check ">
                                <input class="form-check-input remember_me_check " type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>

                                <label class="form-check-label" for="remember">
                                    {{ __('Remember Me') }}
                                </label>
                            </div>
                        
                    </div>

                    <div class="form-group flex items-center flex-col  mb-0">
                            <button type="submit" class="btn btn-primary w-100 pt-0 pb-1" style="font-size: 15px;">
                                {{ __('Login') }}
                            </button>

                            <div class="Or_wrapper w-full position-relative d-flex justify-content-center mt-3 mb-2">
                                <hr class=" login_hr w-100 ">
                                <span class="text-sm position-absolute p-2 bg-white login_or">OR</span> 
                            </div>
                            
                            <a href="" class="text-center hover:no-underline hover:text-blue-900 text-blue-800 w-11/12 py-1" style="font-size: 15px;"><i class="fab fa-facebook-square mr-1 "></i> Continue with Facebook</a>
                            
                            <div class="d-flex flex-column align-items-center " >
                                @if (Route::has('password.request'))
                                    <a class="text-blue-600 font-semibold  py-2 text-sm hover:no-underline hover:text-blue-700" href="{{ route('password.request') }}">
                                        {{ __('Forgot Your Password?') }}
                                    </a>
                                @endif
                                <span class="text-sm">Don't have an account ?<a href="{{ route('register') }}" class="text-blue-600 text-center font-semibold hover:no-underline hover:text-blue-800"> Sign up </a></span>
                                
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
                </form>
            </div>
        </div>
    </div>
    
</div>
@endsection
<script>

</script>