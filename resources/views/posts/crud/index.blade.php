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
      <div class="modalShare">
        <div id="shareModal" class="modal" tabindex="-1" role="dialog">
          <div class="modal-dialog" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title">Choose a receiver</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body">
                @if ($followedUsers != null)
                  @foreach ($followedUsers as $followedUserId)
                  @php
                    $followedUser= \App\User::findorFail($followedUserId);
                    $postId = \App\Post::all()->random()->id;
                  @endphp
                      <ul>
                        <li class="followed_user">{{$followedUser->name}}
                          <form method="POST" action="{{route('chats.store')}}">
                                @csrf
                            <input type="hidden" name="receiver_id" value="{{$followedUserId}}">
                            <input type="hidden" class="form-control" name="sender_id" value="{{session('user_id')}}">
                            <input type="hidden" name="content" value="{{$postId}}">
                            <button type="submit" name="submit" class="btn btn-primary">Send</button>
                            {{-- input[{!! Form::hidden($name, $value, [$options]) !!}] WTF IS THIS--}} 
                          </form>
                          
                        </li>  
                      </ul>
                  @endforeach
                @endif
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
              </div>
            </div>
          </div>
        </div>
    </div>
  </div>

  <div class="table">
      <table class="table">
        <thead>
          <tr>
            <th>N</th>
            <th>Id</th>
            <th>Poster</th>
            <th>Media File</th>
            <th>Description</th>
            <th style="width:170px;" class="text-center">
              <a href="{{ route('posts.create', 'post')}}" class="btn btn-primary m-0">Add Post</a>
              <a href="{{ route('posts.create', 'story')}}" class="btn btn-primary m-0">Add Story</a>
            </th> 

          </tr>
        </thead>
        <tbody>
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
                  <img src="{{ $post['media_file']}}" alt="" class="">
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
                  <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#shareModal">
                    Share
                  </button>
                </td>
                <td>
                  {{-- if publication type is 'post' show option 'share as story', and vice versa --}}
                  @if ($post->type=='post')
                    <form action="{{ route('posts.update', $post->id) }}" method="post" >
                      <input type="hidden" name='type' value='post/story'>
                      <input class="btn btn-primary" type="submit" value="Share as story" />
                      @csrf
                      @method('PATCH')
                    </form> 
                  @elseif ($post->type=='story')
                    <form action="{{ route('posts.update', $post->id) }}" method="post" >
                      <input type="hidden" name='type' value='story/post'>
                      <input class="btn btn-primary" type="submit" value="Save as post" />
                      @csrf
                      @method('PATCH')
                    </form> 
                  @endif
                </td>
                <td class="text-center">
                  <form action="{{ route('posts.destroy',$post->id) }}" method="post" >
                    <input type="hidden" name ='type' value="post">
                    <input class="btn btn-danger" type="submit" value="Delete Post" />
                    @method('delete')
                    @csrf
                  </form>
                </td>
                <td class="text-center">
                  <form action="{{ route('posts.destroy',$post->id) }}" method="post" >
                    <input type="hidden" name ='type' value="story">
                    <input class="btn btn-danger" type="submit" value="Delete Story" />
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
  </div>    
      
          
      
@endsection
