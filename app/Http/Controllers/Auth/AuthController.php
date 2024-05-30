<?php

namespace App\Http\Controllers\Auth;

use App\Actions\EmailVerificationUrl;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegistrationRequest;
use App\Models\User;
use App\Notifications\VerifyEmail;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class AuthController extends Controller
{
	public function register(RegistrationRequest $request, EmailVerificationUrl $emailVerificationUrl): JsonResponse
	{
		$credentials = $request->validated();

		$user = User::create($credentials);

		$user->notify(new VerifyEmail($emailVerificationUrl->handle($user)));

		return response()->json(['title' => 'Registration Link Sent', 'message' => 'You have successfully created an account. Check your inbox for email verification.'], 201);
	}

	public function login(LoginRequest $request): JsonResponse
	{
		$credentials = $request->validated();
		$remember = $credentials['remember'] ?? false;

		if (Auth::attempt(['email' => $credentials['email'], 'password' => $credentials['password']], $remember)) {
			$request->session()->regenerate();

			return response()->json(['title' => 'Login Success', 'message' => 'Your have successfully logged in.'], 200);
		}
		return response()->json(['password' => 'Email or password is incorrect'], 404);
	}

	public function logout(Request $request): JsonResponse
	{
		Auth::guard('web')->logout();

		$request->session()->invalidate();

		$request->session()->regenerateToken();

		return response()->json(['title' => 'Logout Success', 'message' => 'Your have successfully logged out.'], 200);
	}
}
