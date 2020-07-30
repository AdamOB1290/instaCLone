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
    </div>
      <table class="table">
        <thead>
          <tr>
            <th>N</th>
            <th>Id</th>
            <th>Name</th>
            <th>Content</th>
            {{-- <th>Email</th> --}}
            <th style="width:170px;" class="text-center">
              <a href="{{ route('comments.create')}}" class="btn btn-primary m-0">Add Comment</a>
            </th> 

          </tr>
        </thead>
        <tbody>
            @foreach ($comments as $key => $comment)
            <tr>
                <th><a href="{{ route('comments.show',$comment->id) }}">{{$key+1}} </a></th>
                <td><a href="{{ route('comments.show',$comment->id) }}">{{'#' . $comment['id']}} </a></td>
                <td>{{$comment->user['name']}}</td>
                <td><a href="{{ route('comments.edit',[$comment->id,$comment->user_id]) }}">{{$comment['content']}} </a></td>
                <td class="text-center">
                  <a href="{{ route('comments.create', [$comment->id,$comment->post_id])}}" class="btn btn-primary">Reply</a>
                </td>     
                <td class="text-center">
                  <form action="{{ route('comments.destroy',$comment->id) }}" method="post" >
                    <input class="btn btn-danger" type="submit" value="Delete" />
                    @method('delete')
                    @csrf
                </form>
                </td>

            </tr>      
            @endforeach

        </tbody>
      </table>
      
      
          
      
@endsection
