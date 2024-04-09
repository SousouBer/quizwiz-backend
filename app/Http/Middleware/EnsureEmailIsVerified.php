<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureEmailIsVerified
{
	public function handle(Request $request, Closure $next): Response|JsonResponse
	{
		$user = User::firstWhere('email', $request->only('email'));

		if (!$user->hasVerifiedEmail()) {
			return response()->json(['message' => 'Your email address is not verified.'], 409);
		}

		return $next($request);
	}
}
