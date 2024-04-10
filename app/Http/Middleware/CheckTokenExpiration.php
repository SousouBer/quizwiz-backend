<?php

namespace App\Http\Middleware;

use Carbon\Carbon;
use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckTokenExpiration
{
	public function handle(Request $request, Closure $next): Response|JsonResponse
	{
		$expiration = Carbon::createFromTimestamp($request->query('expires_at'));

		if (Carbon::now()->greaterThan($expiration)) {
			return response()->json(['title' => 'Token Expired', 'message' => 'Current token has been expired. You can resend a new one below.'], 403);
		}

		return $next($request);
	}
}
