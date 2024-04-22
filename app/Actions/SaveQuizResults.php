<?php

namespace App\Actions;

use App\Models\Quiz;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SaveQuizResults
{
	public static function handle(Quiz $quiz, int $timeInMinutes, int $score, int $wrongAnswers, array $arr): array
	{
		if (Auth::check()) {
			User::findOrFail(Auth::id())->quizzes()->attach($quiz->id, [
				'time_taken' => $timeInMinutes,
				'score'      => $score,
			]);
		} else {
			DB::table('quiz_user')->insert([
				'quiz_id'    => $quiz->id,
				'time_taken' => $timeInMinutes,
				'score'      => $score,
			]);
		}
		return [
			'title'           => $quiz->title,
			'time'            => $arr['time'],
			'correct_answers' => $score,
			'wrong_answers'   => $wrongAnswers,
		];
	}
}
