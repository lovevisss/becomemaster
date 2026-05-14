@extends('layouts.main')

@section('content')
    <main class="container py-4">
        <div class="mb-3 d-flex justify-content-between align-items-center">
            <div>
                <h4 class="mb-1">添加新用户</h4>
                <p class="text-muted mb-0">填写基础信息并绑定所属公司</p>
            </div>
            <a href="{{ route('users.index') }}" class="btn btn-outline-secondary btn-sm">返回列表</a>
        </div>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="card shadow-sm">
            <div class="card-body">
                <form action="{{ route('users.store') }}" method="POST" class="row g-3">
                    @csrf

                    <div class="col-md-6">
                        <label for="name" class="form-label">姓名 <span class="text-danger">*</span></label>
                        <input type="text" id="name" name="name" class="form-control" value="{{ old('name') }}" required>
                    </div>

                    <div class="col-md-6">
                        <label for="phone" class="form-label">电话</label>
                        <input type="text" id="phone" name="phone" class="form-control" value="{{ old('phone') }}">
                    </div>

                    <div class="col-md-6">
                        <label for="email" class="form-label">邮箱</label>
                        <input type="email" id="email" name="email" class="form-control" value="{{ old('email') }}">
                    </div>

                    <div class="col-md-6">
                        <label for="role_id" class="form-label">角色</label>
                        <select id="role_id" name="role_id" class="form-select">
                            <option value="4" {{ old('role_id', '4') == '4' ? 'selected' : '' }}>普通用户</option>
                            <option value="3" {{ old('role_id') == '3' ? 'selected' : '' }}>申请人</option>
                            <option value="2" {{ old('role_id') == '2' ? 'selected' : '' }}>管理员</option>
                            <option value="1" {{ old('role_id') == '1' ? 'selected' : '' }}>超级管理员</option>
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label for="company_id" class="form-label">所属公司</label>
                        <select id="company_id" name="company_id" class="form-select">
                            <option value="">未绑定公司</option>
                            @foreach($companies as $company)
                                <option value="{{ $company->id }}" {{ (string)old('company_id') === (string)$company->id ? 'selected' : '' }}>{{ $company->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label for="password" class="form-label">初始密码（可选）</label>
                        <input type="password" id="password" name="password" class="form-control" placeholder="不填则留空">
                    </div>

                    <div class="col-12 d-flex gap-2">
                        <button type="submit" class="btn btn-primary">保存用户</button>
                        <a href="{{ route('users.index') }}" class="btn btn-outline-secondary">取消</a>
                    </div>
                </form>
            </div>
        </div>
    </main>
@endsection
