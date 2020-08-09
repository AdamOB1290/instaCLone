@extends('layouts.app')
@section('title')
    
@endsection

@section('content')

    <form method="post" action="{{ route('posts.update',$post->id) }}" enctype="multipart/form-data">
        @csrf
        @method('PATCH') 
        @include('posts.crud.form')

        <button type="submit" class="btn btn-primary-outline">Update Post</button>
    </form>

@endsection

