<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>CAS 测试页</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-slate-100">
<main class="mx-auto max-w-4xl px-4 py-10 sm:px-6 lg:px-8">
    <section class="rounded-3xl bg-white p-6 shadow-sm ring-1 ring-slate-200 sm:p-8">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
            <div>
                <p class="text-xs font-semibold uppercase tracking-[0.2em] text-blue-600">CAS 调试页面</p>
                <h1 class="mt-2 text-2xl font-semibold text-slate-900">认证测试入口</h1>
                <p class="mt-2 text-sm leading-6 text-slate-600">
                    这是一个用于检查 CAS 配置与回跳地址的测试页面。若你正在排查登录问题，可先从这里进入登录链路。
                </p>
            </div>
            <div class="rounded-2xl bg-slate-50 px-4 py-3 text-sm text-slate-600 ring-1 ring-slate-200">
                <div><span class="font-medium text-slate-900">APP_URL：</span>{{ config('app.url') }}</div>
                <div class="mt-1"><span class="font-medium text-slate-900">CAS_BASE_URL：</span>{{ config('cas.base_url') }}</div>
            </div>
        </div>

        <div class="mt-6 grid gap-4 sm:grid-cols-2">
            <a href="{{ route('login', ['returnUrl' => '/projects']) }}" class="rounded-2xl border border-blue-200 bg-blue-50 p-4 text-left hover:bg-blue-100">
                <div class="text-sm font-semibold text-blue-700">进入登录</div>
                <div class="mt-1 text-sm text-slate-600">携带 returnUrl 回到项目列表。</div>
            </a>

            <a href="{{ url('/projects') }}" class="rounded-2xl border border-slate-200 bg-slate-50 p-4 text-left hover:bg-slate-100">
                <div class="text-sm font-semibold text-slate-900">查看项目总览</div>
                <div class="mt-1 text-sm text-slate-600">检查登录后是否能正常访问业务页面。</div>
            </a>
        </div>
    </section>
</main>
</body>
</html>

