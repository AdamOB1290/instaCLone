@extends('layouts.app')
@section('title')
    
@endsection

@section('content')
        <form method="POST" action="/users/{{$user->id}}" enctype="multipart/form-data">
            @csrf   
            @method('PATCH')
            <div class="form-group">
                <label for="name">Full Name :</label>
                <input type="text" class="form-control" name="name" placeholder="John Doe" value="{{old('name') ?? $user->name}}">
                @error('name') <div style="color:red;">{{ $message }}</div> @enderror            </div>
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
