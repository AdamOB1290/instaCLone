@extends('layouts.app')
@section('title')
    
@endsection

@section('content')

<div class="container">

    <form method="post" action="{{ route('comments.store') }}">
        @csrf
        @include('comments.crud.form')

        <button type="submit" class="btn btn-primary-outline">Add Comment</button>
    </form>
    
</div>

@endsection

