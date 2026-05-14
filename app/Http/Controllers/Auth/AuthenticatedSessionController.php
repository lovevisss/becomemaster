<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use App\Services\CasService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthenticatedSessionController extends Controller
{
    public function __construct(private readonly CasService $casService)
    {
    }

    public function create(Request $request): RedirectResponse
    {
        if ($request->filled('logout')) {
            return redirect('/')->with('status', 'CAS logout success.');
        }

        $serviceUrl = $this->casService->serviceUrl();
        $ticket = $request->query('ticket');

        if ($ticket) {
            $casUser = $this->casService->validateTicket($serviceUrl, (string) $ticket);
            if (! $casUser) {
                return redirect($this->casService->buildLoginUrl($serviceUrl));
            }

            $user = User::where('xgh', $casUser['user'])->first();
            if (! $user) {
                $user = new User();
                $user->xgh = $casUser['user'];
                $identityTypeName = $casUser['attributes']['identityTypeName'] ?? '';
                $user->role_id = $identityTypeName === '学生' ? 5 : 4;
                $user->department_id = $casUser['attributes']['organizationId'] ?? null;
                $user->name = $casUser['attributes']['userName'] ?? $casUser['user'];
                $user->save();
            }

            Auth::login($user);
            $request->session()->regenerate();

            return redirect()->intended(RouteServiceProvider::HOME);
        }

        return redirect($this->casService->buildLoginUrl($serviceUrl));
    }

    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();
        $request->session()->regenerate();

        return redirect()->intended(RouteServiceProvider::HOME);
    }

    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        $logoutReturnUrl = url(ltrim((string) config('cas.logout_return_path', '/login'), '/')) . '?logout=1';

        return redirect($this->casService->buildLogoutUrl($logoutReturnUrl));
    }
}

