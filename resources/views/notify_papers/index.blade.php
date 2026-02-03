@extends('layouts.main')

@section("content")
    <div class="mt-4 text-right">
        <a href="{{route('notify_papers.create')}}" class="inline-flex items-center text-sm font-medium text-blue-600 hover:text-blue-800 transition-colors">
            创建抄告单
            <svg class="w-4 h-4 ml
    -1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
        </a>
    </div>
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
