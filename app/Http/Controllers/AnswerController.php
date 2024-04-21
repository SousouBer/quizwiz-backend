<?php

namespace App\Http\Controllers;

use App\Models\Quiz;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AnswerController
{
	public function store(Request $request)
	{
		$arr = [
			'quiz_id' => 1,
			'time'    => '4:56',
			'answers' => [1, 2, 7, 9, 10, 12],
		];

		$quiz = Quiz::with('questions.answers')->where('id', $arr['quiz_id'])->first();

		$score = $quiz->answers->where('is_correct', true)->count();

		$wrong = 0;

		foreach ($quiz->questions as $question) {
			$questionPoints = $question->answers->where('is_correct', true)->count();
			$questionCorrectAnswers = $question->answers->where('is_correct', true)->pluck('id')->toArray();
			$providedAnswerIds = $arr['answers'];

			$allCorrectAnswersProvided = empty(array_diff($questionCorrectAnswers, $providedAnswerIds));

			if (!$allCorrectAnswersProvided) {
				$score -= $questionPoints;
				$wrong += $questionPoints;
			}
		}

		$timeInMinutes = 0;

		$time = '0:29';

		list($hours, $minutes) = explode(':', $time);

		$hours = intval($hours);
		$minutes = intval($minutes);

		if ($minutes >= 30) {
			$timeInMinutes = $hours + 1;
		} else {
			$timeInMinutes = $hours;
		}

		$userId = Auth::id();

		DB::table('quiz_user')->insert([
			'user_id'    => $userId,
			'quiz_id'    => $quiz->id,
			'time_taken' => $timeInMinutes,
			'score'      => $score,
		]);

		return response()->json(['title' => $quiz->title, 'time' => $arr['time'], 'correct_answers' => $score, 'wrong_answers' => $wrong]);
	}
}
