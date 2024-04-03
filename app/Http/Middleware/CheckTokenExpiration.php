<?php

namespace App\Http\Middleware;

use Carbon\Carbon;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckTokenExpiration
{
	/**
	 * Handle an incoming request.
	 *
	 * @param \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response) $next
	 */
	public function handle(Request $request, Closure $next): Response
	{
		$expiration = Carbon::createFromTimestamp($request->query('expires_at'));

		if (Carbon::now()->greaterThan($expiration)) {
			return response()->json(['message' => 'Verification token has expired'], 403);
		}

		return $next($request);
	}
}
