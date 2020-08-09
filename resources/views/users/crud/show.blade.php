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

@endsection

      
      
