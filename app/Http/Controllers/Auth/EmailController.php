<?php

namespace App\Http\Controllers\Auth;

use App\Actions\CheckExpiration;
use App\Models\User;
use App\Notifications\VerifyEmail;
use Carbon\Carbon;
use Illuminate\Auth\Events\Verified;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;

class EmailController extends Controller
{
	public function verifyEmail(Request $request, int $id, string $hash): JsonResponse
	{
		$user = User::find($id);

		if (!hash_equals((string) $hash, sha1($user->getEmailForVerification()))) {
			return response()->json(['title' => 'Invalid Credentials', 'message' => 'Invalid verification hash'], 400);
		}

		if ($user->hasVerifiedEmail()) {
			return response()->json(['title' => 'Already Verified', 'message' => 'Email has already been verified.'], 422);
		}

		$user->markEmailAsVerified();

		event(new Verified($user));

		return response()->json(['title' => 'Verification Successful', 'message' => 'You have successfully verified your email', 200]);
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

	public function checkExpiration(string $email, string $token, CheckExpiration $checkExpiration): JsonResponse
	{
		$invalidToken = $checkExpiration->handle($email, $token);

		if ($invalidToken) {
			return response()->json(['title' => 'Token Expired', 'message' => 'Your token has expired. Request resetting password again.'], 403);
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
