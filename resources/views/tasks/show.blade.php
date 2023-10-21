@extends('layouts.main')

@section('content')
    <h1>{{$task->name}}</h1>
    <article>
        {{$task->description}}
    </article>


    <a href="/tasks">Back</a>
@endsection
