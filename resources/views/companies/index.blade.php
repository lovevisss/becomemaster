@extends('layouts.main')
@section("content")

<main class="container">
    <div class="my-3 p-4 bg-white rounded-xl shadow-sm transition-all duration-300 hover:shadow-md">
        <h6 class="border-bottom border-gray-200 pb-2 mb-3 text-gray-800 font-medium">单位列表</h6>

        @foreach ($companies as $company)
            <div class="d-flex text-gray-600 pt-3 pb-2 border-b border-gray-100 last:border-0 last:pb-0 hover:bg-gray-50 rounded-lg px-2 -mx-2 transition-colors">
                <!-- 单位图标 -->
                <div class="flex-shrink-0 me-3">
                    <div class="w-10 h-10 rounded-md bg-blue-50 flex items-center justify-center">
                        <svg class="w-5 h-5 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M3 6a2 2 0 012-2h10a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2V6zM13 9a1 1 0 100 2h-4a1 1 0 100-2h4z"></path>
                        </svg>
                    </div>
                </div>

                <!-- 单位信息 -->
                <div class="flex-grow">
                    @if($company->user)
                        <p class="text-sm font-medium text-gray-900 mb-0.5">{{ $company->user->name }}</p>
                    @endif
                    <a href="/company/{{ $company->id }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium transition-colors">
                        {{ $company->name }}
                    </a>
                </div>

                <!-- 操作按钮 -->
                <div class="flex-shrink-0 ml-2 opacity-0 group-hover:opacity-100 transition-opacity">
                    <button class="text-gray-400 hover:text-gray-600 p-1 rounded-full hover:bg-gray-100">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"></path>
                        </svg>
                    </button>
                </div>
            </div>
        @endforeach

        <!-- 空状态处理 -->
        @empty($companies)
            <div class="py-8 text-center text-gray-500">
                <svg class="w-12 h-12 mx-auto mb-3 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 8a2 2 0 00-1-1.73l-7-4a2 2 0 00-2 0l-7 4A2 2 0 003 8v8a2 2 0 002 2h14a2 2 0 002-2V8z"></path>
                </svg>
                <p>暂无单位数据</p>
            </div>
        @endempty

        <div class="mt-4 text-right">
            <a href="{{route('company.create')}}" class="inline-flex items-center text-sm font-medium text-blue-600 hover:text-blue-800 transition-colors">
                添加部门
                <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
            </a>
        </div>
    </div>

</main>

@endsection

