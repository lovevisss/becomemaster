@extends('layouts.main')

@section("content")
    <div id="app">
        <task-list :project='{{$project}}'></task-list>
    </div>
@endsection