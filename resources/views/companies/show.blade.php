@extends('layouts.main')

@section("content")

<main class="container py-4">
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="row g-4">
        <div class="col-md-8">
            <div class="card mb-4">
                <div class="card-header">公司基本信息</div>
                <div class="card-body">
                    <div class="row mb-2">
                        <div class="col-sm-4 text-muted">公司名称</div>
                        <div class="col-sm-8">{{ $company->name ?? '-' }}</div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-sm-4 text-muted">公司地址</div>
                        <div class="col-sm-8">{{ $company->address ?? '-' }}</div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-sm-4 text-muted">公司描述</div>
                        <div class="col-sm-8">{{ $company->description ?? '-' }}</div>
                    </div>
                    <div class="row mb-0">
                        <div class="col-sm-4 text-muted">项目数量</div>
                        <div class="col-sm-8">{{ $company->contracts->count() }}</div>
                    </div>
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-header">Bank Account 信息</div>
                <div class="card-body">
                    @if($company->bankAccounts->isNotEmpty())
                        <div class="table-responsive">
                            <table class="table table-vcenter mb-0">
                                <thead>
                                <tr>
                                    <th>开户行</th>
                                    <th>账号</th>
                                    <th>主账号</th>
                                    <th>创建时间</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($company->bankAccounts as $bankAccount)
                                    <tr>
                                        <td>{{ $bankAccount->name }}</td>
                                        <td>{{ $bankAccount->formatted_number }}</td>
                                        <td>
                                            {!! $bankAccount->is_primary
                                                ? '<span class="badge bg-primary">是</span>'
                                                : '<span class="text-muted">否</span>' !!}
                                        </td>
                                        <td>{{ optional($bankAccount->created_at)->format('Y-m-d H:i') }}</td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-muted mb-3">当前公司还没有 Bank Account 信息，请在下方新增。</p>
                    @endif

                    <form action="{{ route('bank_accounts.store') }}" method="POST" class="row g-3 mt-1">
                        @csrf
                        <input type="hidden" name="company_id" value="{{ $company->id }}">

                        <div class="col-md-6">
                            <label for="bank-name" class="form-label">开户行</label>
                            <input id="bank-name" type="text" name="name" class="form-control" value="{{ old('name') }}" required>
                        </div>

                        <div class="col-md-6">
                            <label for="bank-number" class="form-label">银行账号</label>
                            <input
                                id="bank-number"
                                type="text"
                                name="number"
                                class="form-control"
                                value="{{ trim(chunk_split(preg_replace('/\s+/', '', (string) old('number')), 4, ' ')) }}"
                                placeholder="例如：6222 0212 3456 7890"
                                inputmode="numeric"
                                oninput="this.value=this.value.replace(/\D/g,'').replace(/(.{4})/g,'$1 ').trim();"
                                required
                            >
                        </div>

                        <div class="col-12">
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" name="is_primary" id="is-primary" value="1" {{ $company->bankAccounts->isEmpty() ? 'checked' : '' }}>
                                <label class="form-check-label" for="is-primary">设为主账号</label>
                            </div>
                            <button type="submit" class="btn btn-primary">保存 Bank Account</button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="card">
                <div class="card-header">项目列表</div>
                <div class="card-body">
                    @forelse ($company->contracts as $project)
                        <div class="border rounded p-3 mb-3">
                            <h5 class="mb-1">{{ $project->name }}</h5>
                            <p class="text-muted mb-2">{{ $project->description ?: '-' }}</p>
                            <a href="{{ route('contracts.show', $project->id) }}" class="btn btn-sm btn-outline-primary">查看项目</a>
                        </div>
                    @empty
                        <p class="text-muted mb-0">暂无项目。</p>
                    @endforelse
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-header">操作</div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('companies.edit', $company->id) }}" class="btn btn-outline-primary">编辑公司</a>
                        <form id="delete-form" action="{{ route('companies.destroy', [$company->id]) }}" method="POST" onsubmit="return confirm('Are you sure to delete this company!')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-outline-danger w-100">删除公司</button>
                        </form>
                        <a href="{{ route('companies.index') }}" class="btn btn-outline-secondary">返回列表</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

@endsection
