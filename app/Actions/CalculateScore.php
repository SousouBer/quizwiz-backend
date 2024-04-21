<?php

namespace App\Actions;

use App\Models\Quiz;

class CalculateScore
{
	public function handle(Quiz $quiz, array $arr): array
	{
		$score = $quiz->answers->where('is_correct', true)->count();
		$wrongAnswers = 0;

		foreach ($quiz->questions as $question) {
			$questionPoints = $question->answers->where('is_correct', true)->count();
			$questionCorrectAnswers = $question->answers->where('is_correct', true)->pluck('id')->toArray();
			$providedAnswerIds = $arr['answers'];

			$allCorrectAnswersProvided = empty(array_diff($questionCorrectAnswers, $providedAnswerIds));

			if (!$allCorrectAnswersProvided) {
				$score -= $questionPoints;
				$wrongAnswers += $questionPoints;
			}
		}

		return [
			'score'         => $score,
			'wrong_answers' => $wrongAnswers,
		];
	}
}
