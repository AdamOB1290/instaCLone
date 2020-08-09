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
@endsection

    
      
      
