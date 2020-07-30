@extends('layouts.app')
@section('title')
    
@endsection

@section('content')

    <form method="post" action="{{ route('posts.store') }}" enctype="multipart/form-data">
        @csrf
        <input type="hidden" name='user_id' value="{{App\User::all()->random()->id}}">
        @include('posts.crud.form')

        <button type="submit" class="btn btn-primary-outline">Add Post</button>
    </form>


@endsection

