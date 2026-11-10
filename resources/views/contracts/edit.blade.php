@extends('layouts.main')

@section("content")
    <main class="w-full pb-8 pt-4">
        <section class="overflow-hidden rounded-3xl bg-gradient-to-r from-slate-950 via-slate-900 to-blue-900 text-white shadow-lg">
            <div class="contract-edit-hero-grid px-5 py-6 sm:px-6">
                <div class="min-w-0">
                    <div class="inline-flex items-center rounded-full bg-white/10 px-3 py-1 text-xs font-medium text-slate-200 ring-1 ring-white/15">
                        合同编辑中心
                    </div>
                    <h1 class="mt-4 break-words text-3xl font-semibold tracking-tight sm:text-4xl">编辑合同：{{ $contract->name }}</h1>
                    <p class="mt-2 max-w-3xl text-sm leading-7 text-slate-300 sm:text-base">
                        在这里可以调整合同状态、签订信息、金额分类以及补传合同附件。更新后可在当前页面核对结果。
                    </p>
                </div>

                <div class="min-w-0 rounded-2xl border border-white/10 bg-white/10 p-4 backdrop-blur sm:p-5">
                    <div class="contract-edit-summary-grid">
                        <div class="min-w-0">
                            <p class="text-xs uppercase tracking-[0.16em] text-slate-300">当前状态</p>
                            <p class="mt-1 text-lg font-semibold">{{ optional($contract->status)->name ?? '未设置' }}</p>
                        </div>
                        <div class="min-w-0">
                            <p class="text-xs uppercase tracking-[0.16em] text-slate-300">签订公司</p>
                            <p class="mt-1 break-words text-sm font-medium leading-6 text-slate-100">{{ optional($contract->company)->name ?? '未设置' }}</p>
                        </div>
                        <div class="min-w-0">
                            <p class="text-xs uppercase tracking-[0.16em] text-slate-300">签订人</p>
                            <p class="mt-1 break-words text-sm font-medium leading-6 text-slate-100">{{ optional($contract->user)->name ?? '未设置' }}</p>
                        </div>
                        <div class="min-w-0">
                            <p class="text-xs uppercase tracking-[0.16em] text-slate-300">签订日期</p>
                            <p class="mt-1 text-sm font-medium text-slate-100">{{ $contract->signing_date ?? '未设置' }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <div class="mt-6 flex flex-wrap items-center justify-between gap-3 rounded-2xl border border-slate-200 bg-white px-5 py-4 shadow-sm">
            <div>
                <p class="text-sm font-semibold text-slate-900">合同编号：{{ $contract->code ?: '未填写' }}</p>
                <p class="mt-1 text-sm text-slate-500">合同年份：{{ $contract->year }} · 可在下方直接修改合同状态与附件。</p>
            </div>
            <div class="flex flex-wrap items-center gap-3">
                <a
                    href="{{ route('contracts.big-pay-form', $contract->id) }}"
                    class="inline-flex items-center rounded-xl bg-blue-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-blue-700"
                >
                    导出大额申请单
                </a>

                <a href="{{ route('projects.index') }}" class="inline-flex items-center rounded-xl border border-slate-300 px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50">
                    返回项目总览
                </a>
            </div>
        </div>
        <div class="mt-6">
            <contract :contract='@json($contract)'></contract>
        </div>
    </main>

    <style>
        .contract-edit-hero-grid,
        .contract-edit-summary-grid {
            display: grid;
            gap: 1.5rem;
        }

        .contract-edit-summary-grid {
            gap: 1rem;
        }

        @media (min-width: 640px) {
            .contract-edit-summary-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }
        }

        @media (min-width: 1280px) {
            .contract-edit-hero-grid {
                align-items: end;
                grid-template-columns: minmax(0, 1fr) minmax(360px, 420px);
            }
        }
    </style>
@endsection
