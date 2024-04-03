<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegistrationRequest;
use App\Models\User;
use App\Notifications\VerifyEmail;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\URL;

class AuthController extends Controller
{
	public function store(RegistrationRequest $request): JsonResponse
	{
		$credentials = $request->validated();

		$user = User::create($credentials);

		$user->notify(new VerifyEmail($this->verificationUrl($user)));

		return response()->json(['message' => 'User created successfully'], 201);
	}

	public function login(LoginRequest $request): JsonResponse
	{
		$credentials = $request->validated();

		if (Auth::attempt($credentials)) {
			$request->session()->regenerate();

			return response()->json(['message' => 'User logged in successfully'], 200);
		}

		return response()->json(['password' => 'Email or password is incorrect'], 404);
	}

	public function destroy(Request $request): JsonResponse
	{
		Auth::logout();

		$request->session()->invalidate();

		$request->session()->regenerateToken();

		return response()->json(['message' => 'Logged out successfully']);
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
