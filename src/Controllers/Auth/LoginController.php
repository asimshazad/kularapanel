<?php

namespace asimshazad\simplepanel\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    public function __construct()
    {
        $this->middleware('guest_admin')->except('logout');
    }

    public function loginForm()
    {
        return view('asimshazad::auth.login');
    }

    protected function authenticated(Request $request, $user)
    {
        if ($request->input('auth_user_timezone')) {
            $user->update(['timezone' => $request->input('auth_user_timezone')]);
        }

        activity('Logged In');

        if (auth()->user()->can('Access Admin Panel')) {
            return response()->json(['redirect' => session()->pull('url.intended', route('admin.dashboard'))]);
        }
        return response()->json(['redirect' => session()->pull('url.intended', '/')]);

    }

    protected function loggedOut(Request $request)
    {
        return redirect()->route('admin.login');
    }
}
