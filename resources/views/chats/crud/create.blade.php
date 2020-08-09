@extends('layouts.app')
@section('title')
    
@endsection
@section('content')

<div class="container">

        <form method="POST" action="{{route('chats.store')}}" enctype="multipart/form-data">
            @csrf   
            <div class="form-group">
                <label for="name">Sender ID :</label>
                <input type="hidden" class="form-control" name="sender_id" value="{{old('sender_id') ?? session('user_id')}}">
                @error('sender_id') <div style="color:red;">{{ $message }}</div> @enderror
            </div>
            <div class="form-group">
                <label for="year">Receiver ID :</label>
                <input type="text" class="form-control" name="receiver_id" value="{{old('receiver_id')}}">
                @error('receiver_id') <div style="color:red;">{{ $message }}</div> @enderror
            </div>
            <div class="form-group">
                <label for="year">Content :</label>
                <input type="text" class="form-control" name="content"  value="{{old('content')}}">
                @error('content') <div style="color:red;">{{ $message }}</div> @enderror
            </div>
            <div class="form-group">
                {{-- is going to be done with ck editor which works with vuejs --}}
                <label for="pfp">Upload Media :</label>
                <input type="file" class="form-control" name="media_file" >
                @error('media_file') <div style="color:red;">{{ $message }}</div> @enderror
            </div>
            

            <button type='submit' class='btn btn-primary'>Send</button>
        </form>
    
</div>

@endsection
