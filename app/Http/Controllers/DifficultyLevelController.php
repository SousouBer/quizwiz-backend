<?php

namespace App\Http\Controllers;

use App\Http\Resources\DifficultyLevelResource;
use App\Models\DifficultyLevel;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class DifficultyLevelController
{
	public function index(): AnonymousResourceCollection
	{
		return DifficultyLevelResource::collection(DifficultyLevel::all());
	}
}
