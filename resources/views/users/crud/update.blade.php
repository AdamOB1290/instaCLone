<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>Laravel</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">

        <!-- Styles -->
        <style>
            html, body {
                background-color: #fff;
                color: #636b6f;
                font-family: 'Nunito', sans-serif;
                font-weight: 200;
                height: 100vh;
                margin: 0;
            }

            .full-height {
                height: 100vh;
            }

            .flex-center {
                align-items: center;
                display: flex;
                justify-content: center;
            }

            .position-ref {
                position: relative;
            }

            .top-right {
                position: absolute;
                right: 10px;
                top: 18px;
            }

            .content {
                text-align: center;
            }

            .title {
                font-size: 84px;
            }

            .links > a {
                color: #636b6f;
                padding: 0 25px;
                font-size: 13px;
                font-weight: 600;
                letter-spacing: .1rem;
                text-decoration: none;
                text-transform: uppercase;
            }

            .m-b-md {
                margin-bottom: 30px;
            }
        </style>
    </head>
    <body>
        <form method="POST" action="/users/{{$user->id}}" enctype="multipart/form-data">
            @csrf   
            @method('PATCH')
            <div class="form-group">
                <label for="name">Full Name :</label>
                <input type="text" class="form-control" name="full_name" placeholder="John Doe" value="{{old('full_name') ?? $user->full_name}}">
                @error('full_name') <div style="color:red;">{{ $message }}</div> @enderror            </div>
            <div class="form-group">
                <label for="year">Birthdate :</label>
                <input type="text" class="form-control" name="birthdate" placeholder="DD-MM-YYYY" value="{{old('birthdate') ?? $user->birthdate}}">
                @error('birthdate') <div style="color:red;">{{ $message }}</div> @enderror            </div>
            <div class="form-group">
                <label for="year">Email :</label>
                <input type="text" class="form-control" name="email" placeholder="Example@example.com" value="{{old('email') ?? $user->email}}">
                @error('email') <div style="color:red;">{{ $message }}</div> @enderror 
            
            <div class="form-group">
                <label for="pfp">Profile Picture :</label>
                <input type="file" class="form-control" name="pfp" >
                @error('pfp') <div style="color:red;">{{ $message }}</div> @enderror
            </div></div>
            {{-- <div class="form-group">
                <label for="year">Username :</label>
                <input type="text" class="form-control" name="username" placeholder="User01" value="{{old('username')}}">
                <div style="color:red;">{{ $errors->first('username')}}</div>
            </div>
            <div class="form-group">
                <label for="year">Password :</label>
                <input type="password" class="form-control" name="password" placeholder="8 characters minimum" >
                <div style="color:red;">{{ $errors->first('password')}}</div>
            </div>
            <div class="form-group">
                <label for="year">Password Confirmation :</label>
                <input type="password" class="form-control" name="password_confirmation" placeholder="Has to match Password" >
                <div style="color:red;">{{ $errors->first('password_confirmation')}}</div>
            </div>

             --}}
            
            
            
            <button type='submit' class='btn btn-primary'>Update</button>
        </form>
    </body>
</html>