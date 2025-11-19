@extends('layouts.main')

@section("content")
    @foreach($notifyPapers as $paper)

        <div class="card mb-3 mt-3">
            <div class="card-body">
                <h5 class="card-title">抄告单-{{$paper->issue_date}}</h5>

                <p class="card-text">抄告单文件：<a href="{{Storage::url($paper->path)}}" target="_blank">{{$paper->name}}</a></p>
                <p class="card-text"><small class="text-muted">上传时间：{{$paper->created_at}}</small></p>
            </div>
        </div>

    @endforeach




@endsection
