<?php

namespace App\Http\Controllers;

use App\Http\Requests\ForgotPasswordRequest;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\PasswordResetRequest;
use App\Http\Requests\RegistrationRequest;
use App\Models\User;
use App\Notifications\VerifyEmail;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Password;
// use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\URL;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\DB;

class AuthController extends Controller
{
	public function CheckExpration(string $email, string $token)
	{
		$passwordResetToken = DB::table('password_reset_tokens')
		->where('email', $email)
		->first();

		if (Hash::check($token, $passwordResetToken->token)) {
			$createdAt = Carbon::parse($passwordResetToken->created_at);

			$expirationTime = $createdAt->addMinutes(config('auth.passwords.users.expire'));
			if ($expirationTime->isPast()) {
				return response()->json(['title' => 'Token expired', 'message' => 'Your token has expired. Request resetting password again.'], 403);
			}
		}
	}

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

	public function resetPassword(PasswordResetRequest $request, string $email, string $token): JsonResponse
	{
		$password = $request->validated();

		$user = User::where('email', $email)->firstOrFail();

		$user->forceFill([
			'password' => Hash::make($password['password']),
		])->setRememberToken(Str::random(60));

		$user->save();

		event(new PasswordReset($user));

		return response()->json(['title' => 'Password reset', 'message' => 'Your password has been successfully reset.', 200]);
	}

	public function forgotPassword(ForgotPasswordRequest $request): JsonResponse
	{
		$email = $request->validated();

		$user = User::where('email', $email)->first();

		if (!$user) {
			return response()->json(['email' => 'User with provided email does not exist'], 404);
		}

		$status = Password::sendResetLink(
			$email
		);

		return response()->json(['title' => 'Link sent', 'message' => 'Your email verification link has been sent. Check your inbox!'], 200);
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
