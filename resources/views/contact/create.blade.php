@extends('layouts.app')
@section('title', 'Contact Us')
@section('content')

<div class="container">
    @if (!session()->get('message'))
        <h1>Contact Us</h1> 
        <form method="post" action="/contact">
            @csrf
            <div class="form-group">    
                <label for="first_name">Name</label>
                <input type="text" name="name" value="{{ old('name')}}" class="form-control" />
                <p class="text-danger">{{$errors->first('name')}}</p>
            </div>

            <div class="form-group">    
                <label for="first_name">Email</label>
                <input type="text" name="email" value="{{ old('email')}}" class="form-control"/>
                <p class="text-danger">{{$errors->first('email')}}</p>
            </div>

            <div class="form-group">    
                <label for="first_name">Message</label>
                <textarea type="text" cols="30" rows="6" class="form-control" id="message" name="message">{{ old('message')}}</textarea>
                <p class="text-danger">{{$errors->first('message')}}</p>
            </div>

            <button type="submit" class="btn btn-primary">Send Message</button>
        </form>
    @endif
</div>

@endsection
