<?php

namespace App\Actions;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class CheckExpiration
{
	public function handle(string $email, string $token): bool
	{
		$passwordResetToken = DB::table('password_reset_tokens')
		->where('email', $email)->first();

		if (Hash::check($token, $passwordResetToken->token)) {
			$createdAt = Carbon::parse($passwordResetToken->created_at);

			$expirationTime = $createdAt->addMinutes(config('auth.passwords.users.expire'));

			return $expirationTime->isPast();
		}
	}
}
