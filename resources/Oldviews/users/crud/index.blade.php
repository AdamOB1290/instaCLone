@extends('layouts.app')
@section('title')
    
@endsection

@section('content')
    <table class="table">
        <thead>
          <tr>
            <th>N</th>
            <th>ID</th>
            <th>Full Name</th>
            <th>Email</th>
            <th>Followers</th>
            <th>Followed</th>
            <th class="text-center">
              <a href="{{ route('users.create')}}" class="btn btn-primary m-0">Add User</a>
            </th> 

          </tr>
        </thead>
        <tbody>
            @foreach ($users as $key => $user)
              @php
                      $sessionUser = \App\User::findorFail(session('user_id'));
                      $followedUsers=$sessionUser->followed;
                      
                      
                      if($user['followers']==null)
                      $user['followers'] = []; 
                      
                      if($user['followed']==null)
                      $user['followed'] = []; 

                      if($followedUsers==null)
                      $followedUsers = [];
                  

                  @endphp
            <tr>          
                <th><a href="/users/{{ $user->id }}">{{$key+1}} </a></th>
                <td><a href="/users/{{ $user->id }}">{{'#' . $user['id']}} </a></td>
                <td><a href="/users/{{ $user->id }}/edit">{{$user['name']}} </a></td>
                <td><a href="/users/{{ $user->id }}/edit">{{$user['email']}} </a></td>
                <td><a href="/users/{{ $user->id }}/edit">{{ count($user['followers']) }}</a></td>
                <td><a href="/users/{{ $user->id }}/edit">{{ count($user['followed']) }} </a></td>
                <td class="text-center">
                  @if (!in_array($user->id,$followedUsers))
                    <form action="{{ route('users.follow', [$user->id, session('user_id')]) }}" method="get" >
                      <input class="btn btn-primary" type="submit" value="follow" />
                      @csrf
                      @method('PATCH')
                    </form>
                  @else
                    <form action="{{ route('users.unfollow', [$user->id, session('user_id')]) }}" method="get" >
                      <input class="btn btn-primary" type="submit" value="Unfollow" />
                      @csrf
                      @method('PATCH')
                    </form> 
                  @endif
                  
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
</body>
</html>

    
      
      
