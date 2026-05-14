@extends('layouts.main')

@section("content")
    <!-- 页面标题区域 -->
    <div class="bg-gradient-to-r from-blue-600 to-indigo-700 text-white px-4 py-8 text-center">
        <div class="container mx-auto max-w-4xl">
            <h1 class="display-5 fw fw-bold text-white mb-3">创建新公司</h1>
            <p class="fs-5 opacity-90 max-w-2xl mx-auto">填写以下信息创建新公司，带 <span class="text-amber-300">*</span> 为必填项</p>
        </div>
    </div>

    <!-- 主要内容区域 -->
    <div class="container mx-auto max-w-6xl py-8 px-4">
        <div class="row gap-8">
            <!-- 表单区域 -->
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

                    @php
                        $selectedResponsibleUser = $users->firstWhere('id', (int) old('user_id'));
                        $responsibleUserName = old('responsible_user_name', optional($selectedResponsibleUser)->name);
                    @endphp

                    <form method="POST" action="{{ route('companies.store') }}" class="space-y-5">
                        {{ csrf_field() }}

                        <div class="form-group">
                            <label for="name" class="form-label fw-medium mb-2 d-block">
                                公司名 <span class="text-red-500 required">*</span>
                            </label>
                            <input type="text"
                                   class="form-control w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                   required
                                   id="name"
                                   placeholder="请输入公司名称"
                                   name="name"
                                   value="{{ old('name') }}">
                            <div class="form-text text-gray-500 mt-1 text-sm">
                                请输入准确的公司全称，将用于系统显示和官方文档
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="tax_id" class="form-label fw-medium mb-2 d-block">税号</label>
                            <input type="text"
                                   class="form-control w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                   id="tax_id"
                                   placeholder="请输入公司税号"
                                   name="tax_id"
                                   value="{{ old('tax_id') }}">
                        </div>

                        <div class="form-group">
                            <label for="address" class="form-label fw-medium mb-2 d-block">地址</label>
                            <input type="text"
                                   class="form-control w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                   id="address"
                                   placeholder="请输入公司地址"
                                   name="address"
                                   value="{{ old('address') }}">
                        </div>

                        <div class="form-group">
                            <label for="telephone" class="form-label fw-medium mb-2 d-block">电话</label>
                            <input type="text"
                                   class="form-control w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                   id="telephone"
                                   placeholder="请输入公司联系电话"
                                   name="telephone"
                                   value="{{ old('telephone') }}">
                        </div>

                        <div class="form-group">
                            <label for="description" class="form-label fw-medium mb-2 d-block">公司描述</label>
                            <textarea id="description"
                                      class="form-control w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                      rows="4"
                                      name="description"
                                      placeholder="可选，补充公司描述">{{ old('description') }}</textarea>
                        </div>

                        <div class="form-group">
                            <label for="responsible_user_name" class="form-label fw-medium mb-2 d-block">负责人</label>
                            <input type="hidden" id="user_id" name="user_id" value="{{ old('user_id') }}">
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

                        <hr>
                        <h5 class="mb-3">银行账户（可选）</h5>

                        <div class="form-group">
                            <label for="bank_name" class="form-label fw-medium mb-2 d-block">开户行</label>
                            <input type="text"
                                   class="form-control w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                   id="bank_name"
                                   placeholder="请输入开户行名称"
                                   name="bank_name"
                                   value="{{ old('bank_name') }}">
                        </div>

                        <div class="form-group">
                            <label for="bank_number" class="form-label fw-medium mb-2 d-block">银行账号</label>
                            <input type="text"
                                   class="form-control w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                   id="bank_number"
                                   placeholder="请输入银行账号"
                                   name="bank_number"
                                   value="{{ trim(chunk_split(preg_replace('/\s+/', '', (string) old('bank_number')), 4, ' ')) }}"
                                   inputmode="numeric"
                                   oninput="this.value=this.value.replace(/\D/g,'').replace(/(.{4})/g,'$1 ').trim();">
                            <div class="form-text text-gray-500 mt-1 text-sm">
                                如果填写银行信息，请同时填写开户行与账号。
                            </div>
                        </div>

                        <div class="pt-3 flex gap-3">
                            <button type="submit" class="btn btn-primary px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors">
                                确认创建
                            </button>
                            <a href="/companies" class="btn btn-outline-secondary px-6 py-2.5 border border-gray-300 hover:bg-gray-50 text-gray-700 rounded-lg transition-colors">
                                取消
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- 侧边栏 -->
            <div class="col-md-3">
                <div class="position-sticky top-4 space-y-5">
                    <!-- 操作区域 -->
                    <div class="bg-white rounded-xl shadow-sm p-5 border border-gray-100">
                        <h4 class="text-lg font-medium text-gray-800 mb-4">操作</h4>
                        <ul class="space-y-2">
                            <li>
                                <a href="{{route('companies.index')}}" class="flex items-center text-blue-600 hover:text-blue-800 transition-colors p-2 rounded-md hover:bg-blue-50">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                                    </svg>
                                    返回公司列表
                                </a>
                            </li>
                            <li>
                                <a href="#" class="flex items-center text-gray-600 hover:text-gray-800 transition-colors p-2 rounded-md hover:bg-gray-50">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 16.5V7.5m0 9l-3-3m3 3l3-3"></path>
                                    </svg>
                                    帮助文档
                                </a>
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
