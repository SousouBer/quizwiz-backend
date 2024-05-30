<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use Illuminate\Http\Request;

class UserController
{
	public function user(Request $request): UserResource
	{
		return UserResource::make($request->user());
	}
}
