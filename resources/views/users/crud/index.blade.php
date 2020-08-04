<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

  <title>Document</title>
</head>
<body>
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
                      $user['followers'] = "[]"; // Array added as a string because of JSON conversion problem
                      
                      if($user['followed']==null)
                      $user['followed'] = "[]"; // Array added as a string because of JSON conversion problem

                      if($followedUsers==null)
                      $followedUsers = [];
                      else // laravel failed to convert $followedUsers from JSON(in database) to array (in php)
                      // it works fine for favorite column

                      $followedUsers = json_decode($followedUsers, true);


                  @endphp
            <tr>          
                <th><a href="/users/{{ $user->id }}">{{$key+1}} </a></th>
                <td><a href="/users/{{ $user->id }}">{{'#' . $user['id']}} </a></td>
                <td><a href="/users/{{ $user->id }}/edit">{{$user['name']}} </a></td>
                <td><a href="/users/{{ $user->id }}/edit">{{$user['email']}} </a></td>
                <td><a href="/users/{{ $user->id }}/edit">{{ count(json_decode($user['followers'])) }}</a></td>
                <td><a href="/users/{{ $user->id }}/edit">{{ count(json_decode($user['followed'])) }} </a></td>
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

    
      
      
