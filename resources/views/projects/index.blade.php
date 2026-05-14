@extends('layouts.main')
@section("content")

<main class="w-full pb-8 pt-4 bg-blue-600">
    <div class="rounded-2xl bg-gradient-to-r from-slate-900 via-slate-800 to-blue-900 bg-blue-600 p-5 text-black shadow-lg sm:p-6">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="text-2xl font-bold">项目总览</h1>
                <p class="mt-1 text-sm text-slate-200">按签订日期降序展示，并按年份分组。</p>
            </div>
            <div class="flex flex-wrap gap-2">
                <a href="{{ route('contract.create') }}" class="inline-flex items-center rounded-lg bg-white/20 px-4 py-2 text-sm font-medium text-black backdrop-blur hover:bg-white/30">
                    新建合同
                </a>
                <a href="{{ route('bank_accounts.index') }}" class="inline-flex items-center rounded-lg border border-white/30 px-4 py-2 text-sm font-medium text-black hover:bg-white/10">
                    银行账户管理
                </a>
            </div>
        </div>

        <div class="mt-4 flex flex-wrap gap-2">
            <span class="rounded-full bg-white/15 px-3 py-1 text-xs font-medium text-slate-100">合同数：{{ $contracts->count() }}</span>
            <span class="rounded-full bg-white/15 px-3 py-1 text-xs font-medium text-slate-100">总金额：{{ number_format((float) $contracts->sum('amount'), 2) }}</span>
            @if(!empty($selectedCompany))
                <span class="rounded-full bg-blue-500/50 px-3 py-1 text-xs font-medium text-black">公司筛选：{{ $selectedCompany->name }}</span>
            @endif
        </div>
    </div>

    <div class="mt-4 rounded-2xl bg-white p-4 shadow-sm ring-1 ring-slate-200 sm:p-5">
        <form method="GET" action="{{ route('projects.index') }}" class="grid grid-cols-1 gap-3 xl:grid-cols-12">
            <div class="xl:col-span-3">
                <label for="project_type_id" class="mb-1 block text-sm font-medium text-slate-700">项目类型</label>
                <select id="project_type_id" name="project_type_id" class="w-full rounded-lg border-slate-300 text-sm focus:border-blue-500 focus:ring-blue-500">
                    <option value="">全部项目类型</option>
                    @foreach(($projectTypes ?? collect()) as $type)
                        <option value="{{ $type->id }}" {{ (string)($projectTypeId ?? '') === (string)$type->id ? 'selected' : '' }}>
                            {{ $type->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="xl:col-span-4">
                <label for="company_name" class="mb-1 block text-sm font-medium text-slate-700">公司名称</label>
                <input id="company_name" name="company_name" type="text" value="{{ $companyName ?? '' }}" placeholder="输入公司名称关键词" class="w-full rounded-lg border-slate-300 text-sm focus:border-blue-500 focus:ring-blue-500">
            </div>
            <div class="xl:col-span-5 flex items-end gap-2">
                @if(!empty($companyId))
                    <input type="hidden" name="company_id" value="{{ $companyId }}">
                @endif
                <button type="submit" class="inline-flex items-center rounded-lg bg-slate-900 px-4 py-2 text-sm font-medium text-black hover:bg-slate-800">筛选</button>
                <a href="{{ route('projects.index') }}" class="inline-flex items-center rounded-lg border border-slate-300 px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50">重置</a>
            </div>
        </form>

        @if(!empty($selectedCompany))
            <div class="mt-4 rounded-lg border border-blue-200 bg-blue-50 px-4 py-3 text-sm text-blue-800">
                当前公司：<span class="font-semibold">{{ $selectedCompany->name }}</span>
                <a href="{{ route('projects.index') }}" class="ml-2 font-medium underline">查看全部</a>
            </div>
        @endif
    </div>

    <div class="mt-4 overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-slate-200">
        <div class="overflow-x-auto">
            <table class="min-w-[1320px] w-full divide-y divide-slate-200 text-sm">
                <thead class="bg-slate-50/90">
                    <tr>
                        <th class="px-4 py-3 text-left font-semibold text-slate-700">名称</th>
                        <th class="px-4 py-3 text-left font-semibold text-slate-700 whitespace-nowrap">签订日期</th>
                        <th class="px-4 py-3 text-left font-semibold text-slate-700 whitespace-nowrap">合同类型</th>
                        <th class="px-4 py-3 text-left font-semibold text-slate-700 whitespace-nowrap">项目类型</th>
                        <th class="px-4 py-3 text-left font-semibold text-slate-700">公司名称</th>
                        <th class="px-4 py-3 text-left font-semibold text-slate-700 whitespace-nowrap">合同金额</th>
                        <th class="px-4 py-3 text-left font-semibold text-slate-700 whitespace-nowrap">当前状态</th>
                        <th class="px-4 py-3 text-left font-semibold text-slate-700 whitespace-nowrap">操作</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @php $lastYear = null; @endphp
                    @forelse($contracts as $contract)
                        @php
                            $currentYear = $contract->year;
                            if (empty($currentYear) && !empty($contract->signing_date)) {
                                $currentYear = \Illuminate\Support\Carbon::parse($contract->signing_date)->format('Y');
                            }
                        @endphp

                        @if($currentYear !== $lastYear)
                            <tr>
                                <td colspan="8" class="bg-slate-100 px-4 py-2 text-xs font-semibold uppercase tracking-wide text-slate-600">
                                    {{ $currentYear ?? '未知年份' }} 年
                                </td>
                            </tr>
                            @php $lastYear = $currentYear; @endphp
                        @endif

                        <tr class="hover:bg-slate-50">
                            <td class="px-4 py-3 font-medium text-slate-900">{{ $contract->name }}</td>
                            <td class="px-4 py-3 text-slate-600 whitespace-nowrap">{{ $contract->signing_date }}</td>
                            <td class="px-4 py-3 text-slate-600 whitespace-nowrap">{{ optional($contract->contract_type)->name ?? '-' }}</td>
                            <td class="px-4 py-3 text-slate-600 whitespace-nowrap">{{ optional($contract->project_type)->name ?? '-' }}</td>
                            <td class="px-4 py-3 max-w-[360px]">
                                @if(!empty($contract->company_id) && !empty(optional($contract->company)->name))
                                    <a href="{{ route('projects.index', ['company_id' => $contract->company_id]) }}" class="inline-block max-w-full truncate text-blue-600 hover:text-blue-700 hover:underline" title="{{ $contract->company->name }}">
                                        {{ $contract->company->name }}
                                    </a>
                                @else
                                    <span class="text-slate-600">-</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-slate-600 whitespace-nowrap">{{ number_format((float) $contract->amount, 2) }}</td>
                            <td class="px-4 py-3 whitespace-nowrap">
                                <span class="inline-flex rounded-full bg-slate-100 px-2.5 py-1 text-xs font-medium text-slate-700">{{ optional($contract->status)->name ?? '-' }}</span>
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap">
                                <a href="{{ route('contract.edit', $contract->id) }}" class="text-blue-600 hover:text-blue-700 hover:underline">编辑</a>
                                <span class="mx-1 text-slate-300">|</span>
                                <a href="{{ route('contract.show', $contract->id) }}" class="text-slate-700 hover:text-slate-900 hover:underline">查看</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-4 py-8 text-center text-slate-500">暂无符合条件的项目/合同记录</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</main>

@endsection
