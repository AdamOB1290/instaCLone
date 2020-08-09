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
            <th>ID</th>
            <th>Full Name</th>
            <th>Email</th>

          </tr>
        </thead>
        <tbody>
            <tr>
              @if ($user->pfp)
            <td><img src="{{asset('storage/'.$user->pfp)}}" alt="" style="height:30%"></td>
              @endif
                <td>{{ $user->id}} </td>
                <td><a href="">{{$user['name']}} </a></td>
                <td><a href="">{{$user['email']}} </a></td>
                <td class="text-center">
                  <form action="" method="user" >
                    <input class="btn btn-danger" type="submit" value="Delete" />
                    @method('delete')
                    @csrf
                </form>
                </td>

            </tr>      
         
        </tbody>
      </table>
</body>
</html>

    
      
      
