@extends('layouts.main')

@section('content')
    <h1>All tasks</h1>
    <ul class="list-group">

        @foreach ($tasks as $task)
        {{-- <li>{{link_to("tasks/$task->id", $task->name)}}</li> --}}
        <li class="list-group-item">   
            {{$task->user->name}} 
            <img src="{{gravatar_url($task->user->email)}}" alt="{{$task->user->email}}">
            <a href="tasks/{{$task->id}}">{{$task->name}}</a> <br>
            {{$task->description}} <br>
        </li>
        @endforeach
    </ul>
    <h2>New tasks</h2>
    @include('partials.new-task-form')
@endsection