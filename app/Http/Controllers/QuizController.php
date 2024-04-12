<?php

namespace App\Http\Controllers;

use App\Models\Quiz;
use Illuminate\Http\JsonResponse;

class QuizController
{
	public function index(): JsonResponse
	{
		$quizzes = Quiz::with(['difficultyLevel' => function ($query) {
			$query->select('id', 'title', 'color', 'color_selected', 'background_color', 'background_color_selected');
		}])
		->select('title', 'image', 'points', 'time', 'difficulty_level_id')
		->get();

		return response()->json(['quizzes', $quizzes]);
	}
}
