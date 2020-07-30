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
            <th class="text-center">
              <a href="{{ route('users.create')}}" class="btn btn-primary m-0">Add User</a>
            </th> 

          </tr>
        </thead>
        <tbody>
            @foreach ($users as $key => $user)
            <tr>
                <th><a href="/users/{{ $user->id }}">{{$key+1}} </a></th>
                <td><a href="/users/{{ $user->id }}">{{'#' . $user['id']}} </a></td>
                <td><a href="/users/{{ $user->id }}/edit">{{$user['name']}} </a></td>
                <td><a href="/users/{{ $user->id }}/edit">{{$user['email']}} </a></td>
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

    
      
      
