<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use CasUtil;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): \Illuminate\Contracts\Foundation\Application|\Illuminate\Foundation\Application|RedirectResponse|\Illuminate\Routing\Redirector
    {

        $ticket = isset($_REQUEST["ticket"]) ? $_REQUEST["ticket"] : null;
        if($ticket){
            $casUser = CasUtil::serviceValidate(CAS_SERVER_URL, APP_SERVER_URL, $ticket);
//            dd($casUser);
            $user = User::where('xgh', $casUser->getUser())->first();

//            dd($user);
//            如果不存在，则创建
            if ($user == null) {
                $user = new User();
                $user->xgh = $casUser->getUser();
                $user->role_id = $casUser->getAttributes()['identityTypeName'] == '学生' ? 5 : 4;
                $user->department_id = $casUser->getAttributes()['organizationId'];
                $user->name = $casUser->getAttributes()['userName'];
                $user->save();

            }
            Auth::login($user);
        }

//    dd($_REQUEST);
//    dd(\Illuminate\Support\Facades\Request::all());
        $redirect = CAS_SERVER_URL ."/login?service=".urlencode(mb_convert_encoding(APP_SERVER_URL, "utf-8"));


        return redirect($redirect);
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        return redirect()->intended(RouteServiceProvider::HOME);
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
