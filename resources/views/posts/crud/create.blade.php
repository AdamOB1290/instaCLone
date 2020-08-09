@extends('layouts.app')
@section('title')
    
@endsection

@section('content')

<div class="container">
 
    <form method="post" action="{{ route('posts.store') }}" enctype="multipart/form-data">
        @csrf
        <input type="hidden" name='user_id' value="{{session('user_id')}}">
            <input type="hidden" name='type' value="{{$type}}">
        @include('posts.crud.form')

        <button type="submit" class="btn btn-primary-outline">Add Post</button>
    </form>

</div>

@endsection

