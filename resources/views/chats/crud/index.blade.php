@extends('layouts.app')
@section('title')
    
@endsection
@section('content')
<div class="col-sm-12">

  @if(session()->get('success'))
    <div class="alert alert-success">
      {{ session()->get('success') }}  
    </div>
  @endif    <table class="table">
        <thead>
          <tr>
            <th>N</th>
            <th>pfp</th>
            <th>sender id</th>
            <th>receiver id</th>
            <th>content</th>
            <th class="text-center">
              <a href="{{ route('chats.create')}}" class="btn btn-primary m-0">Add chat</a>
            </th> 

          </tr>
        </thead>
        <tbody>
            @foreach ($chats as $key => $chat)
            <tr>
                <th><a href="/chats/{{ $chat->id }}">{{$key+1}} </a></th>
                <td><img src="{{$chat->user['pfp']}}" alt="" class="rounded-pill" style="height:100px; width:100px;"></td>
                <td><a href="/chats/{{ $chat->id }}">{{ $chat['sender_id']}} </a></td>
                <td><a href="/chats/{{ $chat->id }}/edit">{{$chat['receiver_id']}} </a></td>
                <td><a href="/chats/{{ $chat->id }}/edit">{{$chat['content']}} </a></td>
                <td><a href="{{ route('posts.edit',$chat->id) }}">
                  @if(strstr(mime_content_type('storage/' . $chat->media_file), "image/"))
                      <img src="{{asset('storage/' . $chat['media_file'])}}" alt="" class="img-thumbnail">
                  @elseif(strstr(mime_content_type('storage/' . $chat->media_file), "video/"))
                      <video controls muted class="">
                        <source src="{{asset('storage/' . $chat['media_file'])}}" type="">
                      </video>
                  @endif
                  {{-- <img src="{{ $chat['media_file']}}" alt="" class=""> --}}
                </a></td>
                <td class="text-center">
                  <form action="/chats/{{ $chat->id }}" method="post" >
                    <input class="btn btn-danger" type="submit" value="Delete" />
                    @method('DELETE')
                    @csrf
                </form>
                </td>

            </tr>      
            @endforeach
         
        </tbody>
      </table>
@endsection

    
      
      
