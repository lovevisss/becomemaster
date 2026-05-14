@extends('layouts.main')

@section("content")
    @php
        $currentResponsibleUserId = old('user_id', optional($company->user)->id);
        $selectedResponsibleUser = $users->firstWhere('id', (int) $currentResponsibleUserId);
        $responsibleUserName = old('responsible_user_name', optional($selectedResponsibleUser)->name);
    @endphp

    <div class="bg-gradient-to-r from-blue-600 to-indigo-700 text-white px-4 py-8 text-center">
        <div class="container mx-auto max-w-4xl">
            <h1 class="display-5 fw fw-bold text-white mb-3">编辑公司信息</h1>
            <p class="fs-5 opacity-90 max-w-2xl mx-auto">更新公司资料及负责人信息</p>
        </div>
    </div>

    <div class="container mx-auto max-w-6xl py-8 px-4">
        <div class="row gap-8">
            <div class="col-md-8">
                <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100 transition-all duration-300 hover:shadow-md">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('companies.update', [$company->id]) }}" class="space-y-5">
                        @csrf
                        @method('PUT')

                        <div class="form-group">
                            <label for="name" class="form-label fw-medium mb-2 d-block">公司名 <span class="text-red-500 required">*</span></label>
                            <input type="text" class="form-control w-full px-4 py-2 border border-gray-300 rounded-lg" id="name" name="name" value="{{ old('name', $company->name) }}" required>
                        </div>

                        <div class="form-group">
                            <label for="tax_id" class="form-label fw-medium mb-2 d-block">税号</label>
                            <input type="text" class="form-control w-full px-4 py-2 border border-gray-300 rounded-lg" id="tax_id" name="tax_id" value="{{ old('tax_id', $company->tax_id) }}">
                        </div>

                        <div class="form-group">
                            <label for="address" class="form-label fw-medium mb-2 d-block">地址</label>
                            <input type="text" class="form-control w-full px-4 py-2 border border-gray-300 rounded-lg" id="address" name="address" value="{{ old('address', $company->address) }}">
                        </div>

                        <div class="form-group">
                            <label for="telephone" class="form-label fw-medium mb-2 d-block">电话</label>
                            <input type="text" class="form-control w-full px-4 py-2 border border-gray-300 rounded-lg" id="telephone" name="telephone" value="{{ old('telephone', $company->telephone) }}">
                        </div>

                        <div class="form-group">
                            <label for="description" class="form-label fw-medium mb-2 d-block">公司描述</label>
                            <textarea class="form-control w-full px-4 py-2 border border-gray-300 rounded-lg" id="description" name="description" rows="4">{{ old('description', $company->description) }}</textarea>
                        </div>

                        <div class="form-group">
                            <label for="responsible_user_name" class="form-label fw-medium mb-2 d-block">负责人</label>
                            <input type="hidden" id="user_id" name="user_id" value="{{ $currentResponsibleUserId }}">
                            <input
                                type="text"
                                id="responsible_user_name"
                                name="responsible_user_name"
                                class="form-control"
                                list="responsible-user-options"
                                placeholder="可选择已有负责人，也可直接输入新姓名"
                                value="{{ $responsibleUserName }}"
                                autocomplete="off"
                            >
                            <datalist id="responsible-user-options">
                                @foreach($users as $user)
                                    <option value="{{ $user->name }}"></option>
                                @endforeach
                            </datalist>
                            <div id="responsible-user-hint" class="form-text text-gray-500 mt-1 text-sm">
                                输入已有姓名会自动匹配；输入新姓名会在保存公司时自动创建负责人。
                            </div>
                        </div>

                        <div class="pt-3 flex gap-3">
                            <button type="submit" class="btn btn-primary px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors">保存修改</button>
                            <a href="{{ route('companies.show', $company->id) }}" class="btn btn-outline-secondary px-6 py-2.5 border border-gray-300 hover:bg-gray-50 text-gray-700 rounded-lg transition-colors">取消</a>
                        </div>
                    </form>
                </div>
            </div>

            <div class="col-md-3">
                <div class="position-sticky top-4 space-y-5">
                    <div class="bg-white rounded-xl shadow-sm p-5 border border-gray-100">
                        <h4 class="text-lg font-medium text-gray-800 mb-4">操作</h4>
                        <ul class="space-y-2">
                            <li>
                                <a href="{{ route('companies.index') }}" class="flex items-center text-blue-600 hover:text-blue-800 transition-colors p-2 rounded-md hover:bg-blue-50">返回公司列表</a>
                            </li>
                            <li>
                                <a href="{{ route('companies.show', $company->id) }}" class="flex items-center text-gray-600 hover:text-gray-800 transition-colors p-2 rounded-md hover:bg-gray-50">查看详情页</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        (function () {
            var responsibleInput = document.getElementById('responsible_user_name');
            var userSelect = document.getElementById('user_id');
            var hintEl = document.getElementById('responsible-user-hint');
            var users = @json($users->map(fn ($user) => ['id' => $user->id, 'name' => $user->name])->values());

            if (!responsibleInput || !userSelect || !hintEl) {
                return;
            }

            function syncResponsibleUser() {
                var inputName = (responsibleInput.value || '').trim();

                if (!inputName) {
                    userSelect.value = '';
                    hintEl.textContent = '留空则不设置负责人。';
                    return;
                }

                var matchedUser = users.find(function (user) {
                    return user.name === inputName;
                });

                if (matchedUser) {
                    userSelect.value = String(matchedUser.id);
                    hintEl.textContent = '已匹配现有负责人：' + matchedUser.name;
                } else {
                    userSelect.value = '';
                    hintEl.textContent = '将创建新负责人：' + inputName;
                }
            }

            responsibleInput.addEventListener('input', syncResponsibleUser);
            responsibleInput.addEventListener('change', syncResponsibleUser);
            syncResponsibleUser();
        })();
    </script>
@endsection
