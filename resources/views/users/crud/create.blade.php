@extends('layouts.app')
@section('title')
    
@endsection
@section('content')

<div class="container">

<form method="POST" action="{{route('users.store')}}" enctype="multipart/form-data">
        @csrf   
        <div class="form-group">
            <label for="name">Full Name :</label>
            <input type="text" class="form-control" name="name" placeholder="John Doe" value="{{old('name')}}">
            @error('name') <div style="color:red;">{{ $message }}</div> @enderror
        </div>
        <div class="form-group">
            <label for="birthdate">Birthdate :</label>
            <input type="text" class="form-control" name="birthdate" placeholder="DD-MM-YYYY" value="{{old('birthdate')}}">
            @error('birthdate') <div style="color:red;">{{ $message }}</div> @enderror
        </div>
        <div class="form-group">
            <label for="email">Email :</label>
            <input type="text" class="form-control" name="email" placeholder="Example@example.com" value="{{old('email')}}">
            @error('email') <div style="color:red;">{{ $message }}</div> @enderror
        </div>
        <div class="form-group">
            <label for="username">Username :</label>
            <input type="text" class="form-control" name="username" placeholder="User01" value="{{old('username')}}">
            @error('username') <div style="color:red;">{{ $message }}</div> @enderror
        </div>
        <div class="form-group">
            <label for="password">Password :</label>
            <input type="password" class="form-control" name="password" placeholder="8 characters minimum" >
            @error('password') <div style="color:red;">{{ $message }}</div> @enderror
        </div>
        <div class="form-group">
            <label for="password_confirmation">Password Confirmation :</label>
            <input type="password" class="form-control" name="password_confirmation" placeholder="Has to match Password" >
            @error('password_confirmation') <div style="color:red;">{{ $message }}</div> @enderror
        </div>
        <div class="form-group">
            <label for="pfp">Profile Picture :</label>
            <input type="file" class="form-control" name="pfp" >
            @error('pfp') <div style="color:red;">{{ $message }}</div> @enderror
        </div>
  
        <button type='submit' class='btn btn-primary'>Submit</button>
        </form>
</div>
@endsection
