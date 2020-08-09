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
            <th class="text-center">
              <a href="{{ route('chats.create')}}" class="btn btn-primary m-0">Add chat</a>
            </th> 

          </tr>
        </thead>
        <tbody>
            @foreach ($chats as $key => $chat)
            <tr>
                <th><a href="/chats/{{ $chat->id }}">{{$key+1}} </a></th>
                <th><a href="/chats/{{ $chat->id }}">{{$chat->user->pfp}} </a></th>
                <td><a href="/chats/{{ $chat->id }}">{{ $chat['sender_id']}} </a></td>
                <td><a href="/chats/{{ $chat->id }}/edit">{{$chat['receiver_id']}} </a></td>
                <td><a href="/chats/{{ $chat->id }}/edit">{{$chat['content']}} </a></td>
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
</body>
</html>

    
      
      
