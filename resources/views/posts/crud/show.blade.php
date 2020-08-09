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
 
<h1 class="post text-center">Post</h1>
  <table class="table">
    <thead>
      <tr>
        <th>Id</th>
        <th>Poster</th>
        <th>Media File</th>
        <th>Description</th>
        <th>Type</th>
        <th>Delete</th>
      </tr>
    </thead>
    <tbody>
        <tr>
          <td>{{'#' . $post['id']}}</td>
          <td>{{$post->user['name']}}</td>
          <td><a href="{{ route('posts.edit',$post->id) }}">
            {{-- @if(strstr(mime_content_type('storage/' . $post->media_file), "image/"))
                <img src="{{asset('storage/' . $post['media_file'])}}" alt="" class="img-thumbnail">
            @elseif(strstr(mime_content_type('storage/' . $post->media_file), "video/"))
                <video controls muted class="">
                  <source src="{{asset('storage/' . $post['media_file'])}}" type="">
                </video> --}}
            {{-- @endif --}}
             <img src="{{ $post['media_file']}}" alt="" class="">
          </a></td>          
          <td>{{$post['description']}}</td>
          <td>{{$post['type']}}</td>
          <td class="text-center">
            <form action="{{ route('posts.destroy',$post->id) }}" method="post" >
              <input class="btn btn-danger" type="submit" value="Delete" />
              @method('delete')
              @csrf
            </form>
          </td>
        </tr>       
    </tbody>
  </table>
  <h1 class="Comments text-center">Comments</h1>
  <table class="table">
    <thead>
      <tr>
        <th>N</th>
        <th>Id</th>
        <th>Name</th>
        <th>Content</th>
        {{-- <th>Email</th> --}}
        <th style="width:170px;" class="text-center">
          <a href="{{ route('comments.create',$post->id)}}" class="btn btn-primary m-0">Add Comment</a>
        </th> 

      </tr>
    </thead>
    <tbody>
        @foreach ($post->comments as $key => $comment)
        <tr>
          <th><a href="{{ route('comments.show',$comment->id) }}">{{$key+1}} </a></th>
          <td><a href="{{ route('comments.show',$comment->id) }}">{{'#' . $comment['id']}} </a></td>
          <td>{{$comment->user['name']}}</td>
          <td><a href="{{ route('comments.edit',$comment->id) }}">{{$comment['content']}} </a></td>
          <td class="text-center">
            <a href="{{ route('comments.create', [$comment->id,$comment->post_id])}}" class="btn btn-primary">Reply</a>
          </td>     
          <td class="text-center">
            <form action="{{ route('comments.destroy',$comment->id) }}" method="post" >
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
