<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use App\Services\CasService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class AuthenticatedSessionController extends Controller
{
    public function __construct(private readonly CasService $casService)
    {
    }

    public function create(Request $request): RedirectResponse|Response
    {
        if (! (bool) config('cas.enabled')) {
            return response()->view('auth.login');
        }

        if ($request->filled('logout')) {
            return redirect('/')->with('status', 'CAS logout success.');
        }

        $rawReturnUrl = (string) $request->query('returnUrl', '');
        $returnUrl = $this->sanitizeReturnUrl($rawReturnUrl === '' ? RouteServiceProvider::HOME : $rawReturnUrl);
        $serviceUrl = $rawReturnUrl === ''
            ? $this->casService->serviceUrl()
            : $this->casService->serviceUrlForReturn($returnUrl);
        $ticket = $request->query('ticket');

        if ($ticket) {
            $candidateServiceUrls = array_values(array_unique([
                $serviceUrl,
                $this->casService->serviceUrl(),
                $this->casService->serviceUrlForReturn($returnUrl),
            ]));

            $casUser = null;
            foreach ($candidateServiceUrls as $candidateServiceUrl) {
                $casUser = $this->casService->validateTicket($candidateServiceUrl, (string) $ticket);
                if ($casUser) {
                    break;
                }
            }

            if (! $casUser) {
                return response()->view('auth.cas_error', [
                    'retryUrl' => $this->casService->buildLoginUrl($serviceUrl),
                ], 401);
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

            return redirect()->to($returnUrl);
        }

        return redirect()->away($this->casService->buildLoginUrl($serviceUrl));
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

        if (! (bool) config('cas.enabled')) {
            return redirect('/');
        }

        $logoutReturnUrl = url(ltrim((string) config('cas.logout_return_path', '/login'), '/')) . '?logout=1';

        return redirect($this->casService->buildLogoutUrl($logoutReturnUrl));
    }

    private function sanitizeReturnUrl(string $returnUrl): string
    {
        if ($returnUrl === '' || ! str_starts_with($returnUrl, '/')) {
            return RouteServiceProvider::HOME;
        }

        return $returnUrl;
    }
}

