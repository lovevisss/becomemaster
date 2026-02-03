@extends('layouts.main')

@section('content')
    <div class="container-fluid py-4 px-4">
        <!-- 页面头部 -->
        <div class="d-flex flex-wrap justify-content-between items-center mb-6 gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">用户管理</h1>
                <p class="text-gray-500 mt-1">查看和管理系统中的所有用户账户</p>
            </div>
            <a href="{{ route('users.create') }}" class="btn btn-primary inline-flex items-center px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors shadow-sm">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                添加新用户
            </a>
        </div>

        <!-- 消息提示 -->
        @include('flash::message')
        <div class="clearfix"></div>

        <!-- 用户表格卡片 -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden transition-all duration-300 hover:shadow-md">
            <div class="p-4 border-b border-gray-100 bg-gray-50">
                <div class="flex justify-between items-center">
                    <h2 class="text-lg font-medium text-gray-800">用户列表</h2>
                    <!-- 可添加搜索框等功能 -->
                    <div class="relative">
                        <input type="text" placeholder="搜索用户..." class="pl-9 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors text-sm w-64">
                        <svg class="w-4 h-4 absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- 表格内容 -->
            <div class="overflow-x-auto">
                @include('users.table')
            </div>


        </div>
    </div>
@endsection
