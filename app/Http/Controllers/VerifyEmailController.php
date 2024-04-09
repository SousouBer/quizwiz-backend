<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Auth\Events\Verified;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Request;

class VerifyEmailController extends Controller
{
	// public function __invoke(Request $request, int $id, string $hash): JsonResponse
	// {
	// 	$user = User::find($id);

	// 	if (!hash_equals((string) $hash, sha1($user->getEmailForVerification()))) {
	// 		return response()->json(['title' => 'Invalid Credentials', 'message' => 'Invalid verification hash'], 400);
	// 	}

	// 	if ($user->hasVerifiedEmail()) {
	// 		return response()->json(['title' => 'Already Verified', 'message' => 'Email has already been verified.'], 422);
	// 	}

	// 	$user->markEmailAsVerified();

	// 	event(new Verified($user));

	// 	return response()->json(['title' => 'Verification Successful', 'message' => 'You have successfully verified your email', 200]);
	// }
}
