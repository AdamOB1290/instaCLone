@extends('layouts.app')
@section('title')
    
@endsection

@section('content')

<div class="col-sm-12">

  @if(session()->get('success'))
    <div class="alert alert-success">
      {{ session()->get('success') }}  
    </div>
  @endif
</div>

<h1 class="comment text-center">Comment</h1>
  <table class="table">
    <thead>
      <tr>
        <th>Id</th>
        <th>Name</th>
        <th>Content</th>
        <th>Delete</th>
      </tr>
    </thead>
    <tbody>
        <tr>
          <td>{{'#' . $comment['id']}}</td>
          <td>{{$comment->user['name']}}</td>
          <td>{{$comment['content']}}</td>
          <td class="text-center">
            <form action="{{ route('comments.destroy',$comment->id) }}" method="post" >
              <input class="btn btn-danger" type="submit" value="Delete" />
              @method('delete')
              @csrf
            </form>
          </td>
        </tr>       
    </tbody>
  </table>

  <h1 class="comment text-center">Replies</h1>

  <table class="table">
    <thead>
      <tr>
        <th>N</th>
        <th>Id</th>
        <th>Name</th>
        <th>Content</th>
        {{-- <th>Email</th> --}}
        <th style="width:170px;" class="text-center">
          <a href="{{ route('comments.create')}}" class="btn btn-primary m-0">Reply</a>
        </th> 

      </tr>
    </thead>
    <tbody>
        @foreach ($replies as $key => $reply)
        <tr>
            <th><a href="{{ route('comments.show',$reply->id) }}">{{$key+1}} </a></th>
            <td><a href="{{ route('comments.show',$reply->id) }}">{{'#' . $reply['id']}} </a></td>
            <td>{{$reply->user['name']}}</td>
            <td><a href="{{ route('comments.edit',[$reply->id,$reply->user_id]) }}">{{$reply['content']}} </a></td>
            <td class="text-center">
              <a href="{{ route('comments.create', [$reply->id,$reply->post_id])}}" class="btn btn-primary">Reply</a>
            </td>     
            <td class="text-center">
              <form action="{{ route('comments.destroy',$reply->id) }}" method="post" >
                <input class="btn btn-danger" type="submit" value="Delete" />
                @method('delete')
                @csrf
            </form>
            </td>

        </tr>      
        @endforeach

    </tbody>
  </table>

@endsection
