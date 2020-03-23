<?php

namespace Khludev\KuLaraPanel\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ApiController extends Controller
{
	// api route name
	public $noNeedAuthorization = [
		'api.auth',
		'api.register'
	];
	public function __construct()
	{
		if (request()->route() != null) {
			$this->middleware('api_logger');
			if (!in_array(request()->route()->getName(), $this->noNeedAuthorization)) {
				$this->middleware('auth:api');
				$api_token = @explode(' ', request()->header('authorization'))[1];
				if ($api_token) {
					$this->user = app(config('auth.providers.users.model'))->query()->where('api_token',$api_token)->first();
				}
			}
		}
	}
	public function register(Request $request)
	{
		$failed_response = ['status' => 'failed', 'error' => 'Email has been taken.'];
		$user = app(config('auth.providers.users.model'))->query()->where('email',$request->get('email'))->first();
		if (!$user) {
			app(config('auth.providers.users.model'))->query()->create([
				'name' => $request->get('name'),
				'email' => $request->get('email'),
				'password' => bcrypt(strtolower($request->get('password'))),
				'api_token' => Str::uuid(),
			]);
			if (\Auth::guard('web')->once(['email' => strtolower($request->get('email')), 'password' => strtolower($request->get('password'))])) {
				return response()->json(['status' => 'success', 'apiToken' => auth('web')->user()->api_token]);
			}
		}
		return response()->json($failed_response);
	}
    public function auth(Request $request)
	{
		$failed_response = ['status' => 'failed', 'error' => 'Invalid login credential.'];
		if (!$request->header('authorization')) {
			return response()->json($failed_response);
		}
		list($username, $password) = explode(':', base64_decode(explode(' ', $request->header('authorization'))[1]));
		if (\Auth::once(['email' => strtolower($username), 'password' => strtolower($password)])) {
			return response()->json(['status' => 'success', 'apiToken' => auth('web')->user()->api_token]);
		}
		return response()->json($failed_response);
	}
}
