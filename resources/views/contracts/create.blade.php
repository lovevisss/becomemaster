@extends('layouts.main')

@section("content")
    <main class="container py-3">
        <div class="my-3 p-4 bg-white rounded-xl shadow-sm">
            <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-2">
                <h5 class="mb-0">新建合同</h5>
                <a href="{{ route('projects.index') }}" class="btn btn-outline-secondary btn-sm">返回项目列表</a>
            </div>
            <div class="text-muted small mb-3">请完善合同基础信息并上传合同文件。</div>

            <contract></contract>
        </div>
    </main>
@endsection
