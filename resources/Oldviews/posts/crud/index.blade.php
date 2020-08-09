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
      <table class="table">
        <thead>
          <tr>
            <th>N</th>
            <th>Id</th>
            <th>Poster</th>
            <th>Media File</th>
            <th>Description</th>
            <th style="width:170px;" class="text-center">
              <a href="{{ route('posts.create')}}" class="btn btn-primary m-0">Add Post</a>
            </th> 

          </tr>
        </thead>
        <tbody>
          {{-- <span>{{$posts[0]['id']}}</span> --}}
            @foreach ($posts as $key => $post)
            <tr>
                <th><a href="{{ route('posts.show',$post->id) }}">{{$key+1}} </a></th>
                <td><a href="{{ route('posts.show',$post->id) }}">{{'#' . $post['id']}} </a></td>
                <td>{{$post->user['name']}}</td>
                <td><a href="{{ route('posts.edit',$post->id) }}">
                  {{-- @if(strstr(mime_content_type('storage/' . $post->media_file), "image/"))
                      <img src="{{asset('storage/' . $post['media_file'])}}" alt="" class="img-thumbnail">
                  @elseif(strstr(mime_content_type('storage/' . $post->media_file), "video/"))
                      <video controls muted class="">
                        <source src="{{asset('storage/' . $post['media_file'])}}" type="">
                      </video>
                  @endif --}}
                  
                   <img src="{{ $post['media_file']}}" alt="" class="img-thumbnail">
                </a></td>
                <td><a href="{{ route('posts.edit',$post->id) }}">{{$post['description']}} </a></td>

                <td class="text-center">
                  @php
                      $user = \App\User::findorFail(session('user_id'));
                      $liked_posts=$user->liked['posts'];
                  @endphp
                  @if (!in_array($post->id,$liked_posts))
                    <form action="{{ route('posts.like', [$post->id, session('user_id')]) }}" method="get" >
                      <span>{{$post->likes}}</span> <input class="btn btn-primary" type="submit" value="Like" />
                      @csrf
                      @method('PATCH')
                    </form>
                  @else
                    <form action="{{ route('posts.unlike', [$post->id, session('user_id')]) }}" method="get" >
                      <span>{{$post->likes}}</span> <input class="btn btn-primary" type="submit" value="Unlike" />
                      @csrf
                      @method('PATCH')
                    </form> 
                  @endif
                  
                </td>

                <td class="text-center">
                  @php
                      $user = \App\User::findorFail(session('user_id'));
                      $favorites=$user->favorites;
                      if($favorites==null)
                      $favorites = [];
                      // dd($favorites);
                  @endphp
                  @if (!in_array($post->id,$favorites))
                    <form action="{{ route('posts.favorite', [$post->id, session('user_id')]) }}" method="get" >
                      <input class="btn btn-primary" type="submit" value="Save" />
                      @csrf
                      @method('PATCH')
                    </form>
                  @else
                    <form action="{{ route('posts.unfavorite', [$post->id, session('user_id')]) }}" method="get" >
                      <input class="btn btn-primary" type="submit" value="Unsave" />
                      @csrf
                      @method('PATCH')
                    </form> 
                  @endif
                  
                </td>





                <td class="text-center">
                  <form action="{{ route('posts.destroy',$post->id) }}" method="post" >
                    <input class="btn btn-danger" type="submit" value="Delete" />
                    @method('delete')
                    @csrf
                </form>
                </td>

            </tr>      
            @endforeach
        {{-- $mime = mime_content_type($file); --}}
        {{-- @if(strstr(mime_content_type($posts->media_file), "video/"))
            <video controls muted class="col-12 p-0 ">
              <source src="{{asset('storage/' . $post['media_file'])}}" type="video/mp4">
            </video>
        @elseif(strstr(mime_content_type($posts->media_file), "image/"))
            <img src="{{asset('storage/' . $post['media_file'])}}" alt="" class="img-thumbnail">
        @endif --}}

        </tbody>
      </table>
          
      
@endsection
