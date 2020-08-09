<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Document</title>
</head>
<body>
    <table class="table">
        <thead>
          <tr>
            <th>N</th>
            <th>pfp</th>
            <th>sender id</th>
            <th>receiver id</th>
            <th>content</th>

          </tr>
        </thead>
        <tbody>
            <tr>
                <td>{{ $chat->id}} </td>
                <td><a href="">{{$chat['sender_id']}} </a></td>
                <td><a href="">{{$chat['receiver_id']}} </a></td>
                <td><a href="">{{$chat['content']}} </a></td>
                <td class="text-center">
                  <form action="" method="post" >
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

    
      
      
