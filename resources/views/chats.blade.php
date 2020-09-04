@extends('layouts.app')
@section('title')
    
@endsection
@section('content')
<chat :user="{{Auth::user()}}"></chat>
          
      
@endsection
