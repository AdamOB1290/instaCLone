@extends('layouts.app')

@section('content')
<div class="max-w-md mx-auto h-full flex items-center">
    <div class="w-full">
        <div class="card">
            <div class="card-header text-center text-6xl login_brand text-white-100 border-bottom-0">Instaclone</div>
            <div class="card-body pt-0">
                <form method="POST" class="w-full flex flex-col justify-center mr-0" action="{{ route('register') }}">
                    @csrf

                    <div class="form-group ">

                        <div class="">
                            <input id="name" type="text" placeholder="Full Name" class="login_form form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus>

                            @error('name')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>

                    {{-- <div class="form-group ">
                        <label for="birthdate" class="col-md-4 col-form-label text-md-right">{{ __('Birthdate') }}</label>

                        <div class="">
                            <input id="birthdate" type="text" class="form-control @error('birthdate') is-invalid @enderror" name="birthdate" value="{{ old('birthdate') }}" required autocomplete="birthdate" autofocus>

                            @error('name')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div> --}}

                    <div class="form-group ">

                        <div class="">
                            <input id="email" type="email" placeholder="E-Mail Address" class="login_form form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email">

                            @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group ">

                        <div class="">
                            <input id="username" type="text" placeholder="Username" class="login_form form-control @error('username') is-invalid @enderror" name="username" value="{{ old('username') }}" required autocomplete="username" autofocus>

                            @error('username')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group ">

                        <div class="">
                            <input id="password" type="password" placeholder="Password" class="login_form form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">

                            @error('password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group ">

                        <div class="">
                            <input id="password-confirm" type="password" placeholder="Confirm Password" class="login_form form-control" name="password_confirmation" required autocomplete="new-password">
                        </div>
                    </div>

                    <div class="form-group  mb-0">
                        <div class=" d-flex flex-column align-items-center w-100">
                            <button type="submit" class="btn btn-primary w-100 pt-0 pb-1 mb-3">
                                {{ __('Register') }}
                            </button>                            
                            <span class="text-sm">Don't have an account ?<a href="{{ route('login') }}" class="text-blue-600 text-center font-semibold hover:no-underline hover:text-blue-800"> Sign in </a></span>

                        </div>
                    </div>

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
                    
                </form>

            </div>
        </div>
    </div>
</div>
@endsection
