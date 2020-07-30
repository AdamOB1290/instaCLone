@extends('layouts.app')
@section('title')
    
@endsection

@section('content')

    <form method="post" action="{{ route('comments.store') }}">
        @csrf
        @include('comments.crud.form')

        <button type="submit" class="btn btn-primary-outline">Add Comment</button>
    </form>


@endsection

