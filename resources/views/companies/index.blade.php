@extends('layouts.main')
@section("content")

<main class="container py-3">
    <div class="my-3 p-4 bg-white rounded-xl shadow-sm">
        <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
            <h5 class="mb-0">公司列表</h5>
            <a href="{{ route('companies.create') }}" class="p-1 btn btn-primary btn-sm">添加公司</a>
        </div>

        <form method="GET" action="{{ route('companies.index') }}" class="row g-2 mb-3">
            <div class="col-md-8">
                <input
                    type="text"
                    name="keyword"
                    value="{{ $keyword ?? '' }}"
                    class="form-control"
                    placeholder="搜索公司名/税号/电话/地址/负责人"
                >
            </div>
            <div class="col-md-4 d-flex gap-2">
                <button type="submit" class="btn btn-outline-primary w-50">搜索</button>
                <a href="{{ route('companies.index') }}" class="btn btn-outline-secondary w-50">重置</a>
            </div>
        </form>

        <div class="text-muted small mb-3">
            共 {{ $companies->total() }} 家公司
            @if(!empty($keyword))
                ，当前关键词："{{ $keyword }}"
            @endif
        </div>

        @if($companies->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead>
                    <tr>
                        <th>公司</th>
                        <th>负责人</th>
                        <th>联系方式</th>
                        <th>税号</th>
                        <th>地址</th>
                        <th>项目数</th>
                        <th>银行账户数</th>
                        <th>操作</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($companies as $company)
                        <tr>
                            <td>
                                <a href="{{ route('companies.show', $company->id) }}" class="fw-semibold text-decoration-none">
                                    {{ $company->name }}
                                </a>
                                <div class="small text-muted">
                                    更新于 {{ optional($company->updated_at)->format('Y-m-d H:i') }}
                                </div>
                            </td>
                            <td>{{ optional($company->user)->name ?: '未设置负责人' }}</td>
                            <td>{{ $company->telephone ?: '-' }}</td>
                            <td>{{ $company->tax_id ?: '-' }}</td>
                            <td class="text-truncate" style="max-width: 240px;">{{ $company->address ?: '-' }}</td>
                            <td>{{ $company->contracts->count() }}</td>
                            <td>{{ $company->bank_accounts_count }}</td>
                            <td>
                                <a href="{{ route('companies.show', $company->id) }}" class="btn btn-sm btn-outline-primary me-1">详情</a>
                                <a href="{{ route('companies.edit', $company->id) }}" class="btn btn-sm btn-outline-secondary">编辑</a>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mt-3">
                {{ $companies->links() }}
            </div>
        @else
            <div class="py-5 text-center text-muted">
                未找到匹配的公司信息
            </div>
        @endif
    </div>

</main>

@endsection

