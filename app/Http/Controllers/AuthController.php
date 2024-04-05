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
use Illuminate\Support\Facades\URL;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\DB;

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

		if ($user->hasVerifiedEmail()) {
			if (Auth::attempt($credentials)) {
				$request->session()->regenerate();

				return response()->json(['title' => 'Login Success', 'message' => 'Your have successfully logged in.'], 200);
			}
			return response()->json(['password' => 'Email or password is incorrect'], 401);
		}

		return response()->json(['unverified_user' => 'User has not verified email.'], 403);
	}

	public function logout(Request $request): JsonResponse
	{
		Auth::guard('web')->logout();

		$request->session()->invalidate();

		$request->session()->regenerateToken();

		return response()->json(['title' => 'Logout Success', 'message' => 'Your have successfully logged out.'], 200);
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

		return response()->json(['title' => 'Link Sent', 'message' => 'Your email verification link has been sent. Check your inbox!'], 200);
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

		return response()->json(['title' => 'Password Reset', 'message' => 'Your password has been successfully reset.', 200]);
	}

	public function resendEmailVerification(Request $request, string $email): JsonResponse
	{
		$user = User::where('email', $email)->first();

		if ($user->hasVerifiedEmail()) {
			return response()->json(['title' => 'Verified Email', 'message' => 'Your email is already verified. You can log in.'], 200);
		}

		$user->notify(new VerifyEmail($this->verificationUrl($user)));

		return response()->json(['title' => 'Verification Link Sent', 'message' => 'You have successfully resent an email verification link. Check your inbox!'], 200);
	}

	public function CheckExpration(string $email, string $token)
	{
		$passwordResetToken = DB::table('password_reset_tokens')
		->where('email', $email)
		->first();

		if (Hash::check($token, $passwordResetToken->token)) {
			$createdAt = Carbon::parse($passwordResetToken->created_at);

			$expirationTime = $createdAt->addMinutes(config('auth.passwords.users.expire'));
			if ($expirationTime->isPast()) {
				return response()->json(['title' => 'Token Expired', 'message' => 'Your token has expired. Request resetting password again.'], 403);
			}
		}
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
