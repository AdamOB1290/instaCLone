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

    <table class="table">
        <thead>
          <tr>
            <th>N</th>
            <th>ID</th>
            <th>PFP</th>
            <th>Full Name</th>
            <th>Email</th>
            <th>Followers</th>
            <th>Followed</th>
            <th class="text-center">
              <a href="{{ route('users.create')}}" class="btn btn-primary m-0">Add User</a>
            </th> 
            <th  class="text-center">Notification Preferences</th>
            

          </tr>
        </thead>
        <tbody>
            @foreach ($users as $key => $user)
            @php
              $sessionUser = \App\User::findorFail(session('user_id'));
              $followedUsers=$sessionUser->followed;
              
              
              if($user['followers']==null)
              $user['followers'] = []; // Array added as a string because of JSON conversion problem
              
              if($user['followed']==null)
              $user['followed'] = []; // Array added as a string because of JSON conversion problem
              if($followedUsers==null)
              $followedUsers = [];
              else // laravel failed to convert $followedUsers from JSON(in database) to array (in php)
              // it works fine for favorite column
              $followedUsers = $followedUsers;
            @endphp
            <tr>
                <th><a href="/users/{{ $user->id }}">{{$key+1}} </a></th>
                <td><a href="/users/{{ $user->id }}">{{'#' . $user['id']}} </a></td>

                <td><img src="{{$user['pfp']}}" alt="" class="rounded-pill" style="height:100px; width:100px;"></td>
                <td><a href="/users/{{ $user->id }}/edit">{{$user['name']}} </a></td>
                <td><a href="/users/{{ $user->id }}/edit">{{$user['email']}} </a></td>
                <td><a href="/users/{{ $user->id }}/edit">{{ count($user['followers']) }}</a></td>
                <td><a href="/users/{{ $user->id }}/edit">{{ count($user['followed']) }} </a></td>
                <td class="text-center">
                  @if (!in_array($user->id,$followedUsers))
                    <form action="{{ route('users.follow', [$user->id, session('user_id')]) }}" method="get" >
                      {{-- <span>{{$user->followers}}</span>  --}}
                      <input class="btn btn-primary" type="submit" value="follow" />
                      @csrf
                      @method('PATCH')
                    </form>
                  @else
                    <form action="{{ route('users.unfollow', [$user->id, session('user_id')]) }}" method="get" >
                      {{-- <span>{{$user->followers}}</span>  --}}
                      <input class="btn btn-primary" type="submit" value="Unfollow" />
                      @csrf
                      @method('PATCH')
                    </form> 
                  @endif

                </td>
                <td class="text-center">
                  <form action="{{ route('users.notification_preference', [$user->id, session('user_id'), 'posts']) }}" method="get" >
                    <div class="custom-control custom-switch">
                      <input type="hidden" name ="index" value='posts'>
                      @php
                          
                      @endphp
                      @if (!in_array(session('user_id'), $user->notification_preferences['posts']))
                        <button type="submit" class="btn btn-secondary rounded-pill" >Post</button>
                      @else
                        <button type="submit" class="btn btn-success rounded-pill" >Post</button>
                      @endif
                      
                      
                      
                    </div>
                    @method('PATCH')
                    @csrf
                  </form>
                  <form action="{{ route('users.notification_preference', [$user->id, session('user_id'), 'stories']) }}" method="get" >
                    <div class="custom-control custom-switch">
                      @if (!in_array(session('user_id'), $user->notification_preferences['stories']))
                        <button type="submit" class="btn btn-secondary mt-2 rounded-pill" >Story</button>
                      @else
                        <button type="submit" class="btn btn-success mt-2 rounded-pill" >Story</button>
                      @endif
                      
                    </div>
                    @method('PATCH')
                    @csrf
                  </form>
                </td>

                
                
                <td class="text-center">
                  <form action="/users/{{ $user->id }}" method="post" >
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

