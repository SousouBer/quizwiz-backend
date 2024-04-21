<?php

namespace App\Actions;

use App\Models\Quiz;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SaveQuizResults
{
	public function handle(Quiz $quiz, int $timeInMinutes, int $score, int $wrongAnswers, array $arr): array
	{
		$userId = Auth::id();

		DB::table('quiz_user')->insert([
			'user_id'    => $userId,
			'quiz_id'    => $quiz->id,
			'time_taken' => $timeInMinutes,
			'score'      => $score,
		]);

		return [
			'title'           => $quiz->title,
			'time'            => $arr['time'],
			'correct_answers' => $score,
			'wrong_answers'   => $wrongAnswers,
		];
	}
}
