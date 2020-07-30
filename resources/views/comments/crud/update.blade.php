@extends('layouts.app')
@section('title')
    
@endsection

@section('content')

    <form method="post" action="{{ route('comments.update', $comment->id) }}">
        @csrf
        @method('PATCH') 
        @include('comments.crud.form')

        <button type="submit" class="btn btn-primary-outline">Update Comment</button>
    </form>

@endsection

