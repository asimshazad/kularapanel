<?php

namespace asimshazad\simplepanel\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Http\Request;

class ResetPasswordController extends Controller
{
    use ResetsPasswords;

    public function __construct()
    {
        $this->middleware('guest_admin');
    }

    public function resetForm($token = null)
    {
        return view('asimshazad::auth.passwords.reset', compact('token'));
    }

    protected function sendResetResponse(Request $request, $response)
    {
        flash(['success', trans($response)]);

        return response()->json(['redirect' => route('admin.dashboard')]);
    }

    protected function sendResetFailedResponse(Request $request, $response)
    {
        return response()->json([
            'message' => __l('error_data', 'The given data was invalid.'),
            'errors' => [
                'email' => [trans($response)],
            ],
        ], 422);
    }
}
