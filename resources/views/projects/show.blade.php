@extends('layouts.main')

@section("content")

<div class="bg-dark text-secondary px-4 py-5 text-center ">
    <div class="py-5">
        <h1 class="display-5 fw-bold text-white">{{ $project->name }}</h1>
        <div class="col-lg-6 mx-auto"></div>
    </div>
</div>

<div class="row g-4 py-5">
    <div class="col-md-8">
        <div class="card mb-4">
            <div class="card-header">
                项目基本信息
            </div>
            <div class="card-body">
                <div class="row mb-2">
                    <div class="col-sm-4 text-muted">项目名称</div>
                    <div class="col-sm-8">{{ $project->name ?? '-' }}</div>
                </div>
                <div class="row mb-2">
                    <div class="col-sm-4 text-muted">所属公司</div>
                    <div class="col-sm-8">{{ optional($project->company)->name ?? '-' }}</div>
                </div>
                <div class="row mb-2">
                    <div class="col-sm-4 text-muted">计划天数</div>
                    <div class="col-sm-8">{{ $project->days ? $project->days . ' 天' : '-' }}</div>
                </div>
                <div class="row mb-2">
                    <div class="col-sm-4 text-muted">状态 ID</div>
                    <div class="col-sm-8">{{ $project->status_id ?? '-' }}</div>
                </div>
                <div class="row mb-2">
                    <div class="col-sm-4 text-muted">创建时间</div>
                    <div class="col-sm-8">{{ optional($project->created_at)->format('Y-m-d H:i') ?? '-' }}</div>
                </div>
                <div class="row mb-2">
                    <div class="col-sm-4 text-muted">任务数量</div>
                    <div class="col-sm-8">{{ $project->tasks->count() }}</div>
                </div>
                <div class="row mb-0">
                    <div class="col-sm-4 text-muted">项目说明</div>
                    <div class="col-sm-8">{{ $project->description ?: '-' }}</div>
                </div>
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span>合同导出</span>
                <span class="badge bg-primary">{{ $project->contracts->count() }}</span>
            </div>
            <div class="card-body">
                @if($project->contracts->isEmpty())
                    <p class="text-muted mb-0">当前项目下还没有关联合同，暂时无法导出审核表。</p>
                @else
                    <div class="list-group">
                        @foreach($project->contracts as $contract)
                            <div class="list-group-item">
                                <div class="d-flex flex-column flex-md-row justify-content-between gap-3">
                                    <div>
                                        <h5 class="mb-1">{{ $contract->name }}</h5>
                                        <div class="text-muted small">
                                            合同编号：{{ $contract->code ?: '-' }}
                                            ｜ 合同金额：{{ number_format((float) $contract->amount, 2) }} 元
                                            ｜ 已付金额：{{ number_format((float) ($contract->paid_amount ?? 0), 2) }} 元
                                        </div>
                                        <div class="text-muted small mt-1">
                                            收款单位：{{ optional($contract->company)->name ?: '-' }}
                                            ｜ 经费名称：{{ optional($contract->funding)->name ?: '-' }}
                                        </div>
                                    </div>
                                    <div class="d-flex align-items-start">
                                        <a
                                            href="{{ route('contracts.big-pay-form', $contract->id) }}"
                                            class="btn btn-primary"
                                        >
                                            导出审核表
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>

        <div>
            <h2>
                申请阶段
                <span class="fa fa-vote-yea" style="color: {{ $project->apply_forms->isNotEmpty() ? 'greenyellow' : 'red' }}; font-size: 1em"></span>
            </h2>
            @if($project->apply_forms)
                @foreach($project->apply_forms as $form)
                    <single-apply-file :form="{{ $form }}"></single-apply-file>
                @endforeach
            @endif
            <button class="btn btn-primary mt-2">添加申请表</button>
        </div>
        <hr>
    </div>

    <div class="col-md-4">
        <div class="position-sticky" style="top: 2rem;">
            <div class="p-4 mb-3 bg-body-tertiary rounded">
                <h4 class="fst-italic">Actions</h4>
                <ul class="p-4">
                    <li class="p-1"><a href="/projects/{{ $project->id }}/edit">Edit</a></li>
                    <li class="p-1">
                        <a href="#" onclick="
                            var result = confirm('Are you sure to delete this project!');
                            if(result){
                                event.preventDefault();
                                document.getElementById('delete-form').submit();
                            }">Delete</a>
                        <form id="delete-form" action="{{ route('projects.destroy', [$project->id]) }}" method="POST" style="display: none">
                            <input type="hidden" name="_method" value="delete">
                            {{ csrf_field() }}
                        </form>
                    </li>
                    <li class="p-1"><a href="{{ route('projects.index') }}">Back</a></li>
                </ul>
            </div>

            <div>
                <h4 class="fst-italic">All Members</h4>
                <ul class="list-unstyled">
                    <li>
                        <a class="d-flex flex-column flex-lg-row gap-3 align-items-start align-items-lg-center py-3 link-body-emphasis text-decoration-none border-top" href="#">
                            <svg class="bd-placeholder-img" width="100%" height="96" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" preserveAspectRatio="xMidYMid slice" focusable="false"><rect width="100%" height="100%" fill="#777"></rect></svg>
                            <div class="col-lg-8">
                                <h6 class="mb-0">Example blog post title</h6>
                                <small class="text-body-secondary">January 15, 2023</small>
                            </div>
                        </a>
                    </li>
                    <li>
                        <a class="d-flex flex-column flex-lg-row gap-3 align-items-start align-items-lg-center py-3 link-body-emphasis text-decoration-none border-top" href="#">
                            <svg class="bd-placeholder-img" width="100%" height="96" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" preserveAspectRatio="xMidYMid slice" focusable="false"><rect width="100%" height="100%" fill="#777"></rect></svg>
                            <div class="col-lg-8">
                                <h6 class="mb-0">This is another blog post title</h6>
                                <small class="text-body-secondary">January 14, 2023</small>
                            </div>
                        </a>
                    </li>
                    <li>
                        <a class="d-flex flex-column flex-lg-row gap-3 align-items-start align-items-lg-center py-3 link-body-emphasis text-decoration-none border-top" href="#">
                            <svg class="bd-placeholder-img" width="100%" height="96" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" preserveAspectRatio="xMidYMid slice" focusable="false"><rect width="100%" height="100%" fill="#777"></rect></svg>
                            <div class="col-lg-8">
                                <h6 class="mb-0">Longer blog post title: This one has multiple lines!</h6>
                                <small class="text-body-secondary">January 13, 2023</small>
                            </div>
                        </a>
                    </li>
                </ul>
            </div>

            <div class="p-4">
                <h4 class="fst-italic">Archives</h4>
                <ol class="list-unstyled mb-0">
                    <li><a href="#">March 2021</a></li>
                    <li><a href="#">February 2021</a></li>
                    <li><a href="#">January 2021</a></li>
                    <li><a href="#">December 2020</a></li>
                    <li><a href="#">November 2020</a></li>
                    <li><a href="#">October 2020</a></li>
                    <li><a href="#">September 2020</a></li>
                    <li><a href="#">August 2020</a></li>
                    <li><a href="#">July 2020</a></li>
                    <li><a href="#">June 2020</a></li>
                    <li><a href="#">May 2020</a></li>
                    <li><a href="#">April 2020</a></li>
                </ol>
            </div>

            <div class="p-4">
                <h4 class="fst-italic">Elsewhere</h4>
                <ol class="list-unstyled">
                    <li><a href="#">GitHub</a></li>
                    <li><a href="#">Twitter</a></li>
                    <li><a href="#">Facebook</a></li>
                </ol>
            </div>
        </div>
    </div>
</div>

@endsection
