<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegistrationRequest;
use App\Models\User;
use App\Notifications\VerifyEmail;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\URL;

class RegisteredUserController extends Controller
{
	public function store(RegistrationRequest $request): JsonResponse
	{
		$credentials = $request->validated();

		// $user = User::create($credentials);

		// $user->notify(new VerifyEmail($this->verificationUrl($user)));

		return response()->json(['message' => 'User created successfully'], 201);
	}

	protected function verificationUrl($user)
	{
		$expiration = Carbon::now()->addMinutes(1);

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
