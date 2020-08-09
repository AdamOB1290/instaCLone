<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>Laravel</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">

        <!-- Styles -->
        <style>
            html, body {
                background-color: #fff;
                color: #636b6f;
                font-family: 'Nunito', sans-serif;
                font-weight: 200;
                height: 100vh;
                margin: 0;
            }

            .full-height {
                height: 100vh;
            }

            .flex-center {
                align-items: center;
                display: flex;
                justify-content: center;
            }

            .position-ref {
                position: relative;
            }

            .top-right {
                position: absolute;
                right: 10px;
                top: 18px;
            }

            .content {
                text-align: center;
            }

            .title {
                font-size: 84px;
            }

            .links > a {
                color: #636b6f;
                padding: 0 25px;
                font-size: 13px;
                font-weight: 600;
                letter-spacing: .1rem;
                text-decoration: none;
                text-transform: uppercase;
            }

            .m-b-md {
                margin-bottom: 30px;
            }
        </style>
    </head>
    <body>
        <form method="POST" action="/chats/{{$chat->id}}" enctype="multipart/form-data">
            @csrf   
            @method('PATCH')
            <div class="form-group">
                <label for="name">Sender ID :</label>
                <input type="text" class="form-control" name="sender_id" value="{{old('sender_id') ?? $chat->sender_id}}">
                @error('sender_id') <div style="color:red;">{{ $message }}</div> @enderror
            </div>
            <div class="form-group">
                <label for="year">Receiver ID :</label>
                <input type="text" class="form-control" name="receiver_id" value="{{old('receiver_id') ?? $chat->receiver_id}}">
                @error('receiver_id') <div style="color:red;">{{ $message }}</div> @enderror
            </div>
            <div class="form-group">
                <label for="year">Content :</label>
                <input type="text" class="form-control" name="content"  value="{{old('content') ?? $chat->content}}">
                @error('content') <div style="color:red;">{{ $message }}</div> @enderror
            </div>
            
            
            
            <button type='submit' class='btn btn-primary'>Update</button>
        </form>
    </body>
</html>
