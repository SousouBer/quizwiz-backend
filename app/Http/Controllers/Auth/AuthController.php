<?php

namespace App\Http\Controllers\Auth;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegistrationRequest;
use App\Models\User;
use App\Notifications\VerifyEmail;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\URL;
use Illuminate\Http\Request;

class AuthController extends Controller
{
	public function register(RegistrationRequest $request): JsonResponse
	{
		$credentials = $request->validated();

		$user = User::create($credentials);

		$user->notify(new VerifyEmail($this->verificationUrl($user)));

		return response()->json(['title' => 'Registration Link Sent', 'message' => 'You have successfully created an account. Check your inbox for email verification.'], 201);
	}

	public function login(LoginRequest $request): JsonResponse
	{
		$credentials = $request->validated();

		$user = User::where('email', $credentials['email'])->first();

		if (Auth::attempt($credentials)) {
			$request->session()->regenerate();

			return response()->json(['title' => 'Login Success', 'message' => 'Your have successfully logged in.'], 200);
		}
		return response()->json(['password' => 'Email or password is incorrect'], 401);
	}

	public function logout(Request $request): JsonResponse
	{
		Auth::guard('web')->logout();

		$request->session()->invalidate();

		$request->session()->regenerateToken();

		return response()->json(['title' => 'Logout Success', 'message' => 'Your have successfully logged out.'], 200);
	}

	protected function verificationUrl(User $user): string
	{
		$expiration = Carbon::now()->addMinutes(120);

		return URL::temporarySignedRoute(
			'verification.verify',
			$expiration,
			[
				'id'         => $user->getKey(),
				'hash'       => sha1($user->email),
				'expires_at' => $expiration->timestamp,
			]
		);
	}
}
