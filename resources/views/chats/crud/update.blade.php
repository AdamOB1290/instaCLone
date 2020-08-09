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
@endsection
