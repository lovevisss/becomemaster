<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>CAS 登录失败</title>
    @vite(['resources/css/app.css'])
</head>
<body class="min-h-screen bg-slate-100 px-4 py-10">
<div class="mx-auto max-w-xl rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200 sm:p-8">
    <h1 class="text-xl font-semibold text-slate-900">CAS 登录失败</h1>
    <p class="mt-3 text-sm leading-6 text-slate-600">
        系统已停止自动重定向，避免出现无限跳转。当前回调票据验证失败，请检查 CAS 服务白名单与应用回调地址是否一致。
    </p>

    <div class="mt-5 rounded-xl border border-amber-200 bg-amber-50 px-4 py-3 text-sm text-amber-800">
        建议确认：`APP_URL`、`CAS_SERVICE_PATH` 以及 CAS 平台中登记的 service 地址必须完全一致（包含协议、域名、端口与路径）。
    </div>

    <div class="mt-6 flex flex-wrap gap-3">
        <a href="{{ $retryUrl }}" class="inline-flex items-center rounded-xl bg-slate-900 px-4 py-2 text-sm font-medium text-white hover:bg-slate-800">
            重新发起 CAS 登录
        </a>
        <a href="{{ url('/teststatus') }}" class="inline-flex items-center rounded-xl border border-slate-300 px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50">
            打开测试页
        </a>
    </div>
</div>
</body>
</html>

