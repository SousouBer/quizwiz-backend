<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegistrationRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;

class RegisteredUserController extends Controller
{
	public function store(RegistrationRequest $request): JsonResponse
	{
		$credentials = $request->validated();

		User::create($credentials);

		return response()->json(['message' => 'User created successfully'], 201);
	}
}
