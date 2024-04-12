<?php

namespace App\Http\Controllers;

use App\Models\DifficultyLevel;
use Illuminate\Http\JsonResponse;

class DifficultyLevelController
{
	public function index(): JsonResponse
	{
		$difficultyLevels = DifficultyLevel::select('id', 'color', 'color_selected', 'background_color', 'background_color_selected')->get();

		return response()->json(['levels' => $difficultyLevels]);
	}
}
