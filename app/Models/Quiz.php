<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class Quiz extends Model
{
	use HasFactory;

	protected $guarded = ['id'];

	protected $hidden = ['created_at', 'updated_at', 'difficulty_level_id', 'pivot'];

	public function users(): BelongsToMany
	{
		return $this->belongsToMany(User::class)->withPivot('time_taken', 'score');
	}

	public function categories(): BelongsToMany
	{
		return $this->belongsToMany(Category::class);
	}

	public function difficultyLevel(): BelongsTo
	{
		return $this->belongsTo(DifficultyLevel::class);
	}

	public function questions(): HasMany
	{
		return $this->hasMany(Question::class);
	}

	public function answers(): HasManyThrough
	{
		return $this->hasManyThrough(Answer::class, Question::class);
	}

	public function calculateScore(array $arr, string $time): array
	{
		$score = $this->answers->where('is_correct', true)->count();
		$wrongAnswers = 0;

		foreach ($this->questions as $question) {
			$questionPoints = $question->answers->where('is_correct', true)->count();
			$questionCorrectAnswers = $question->answers->where('is_correct', true)->pluck('id')->toArray();
			$providedAnswerIds = $arr['answers'];

			$allCorrectAnswersProvided = empty(array_diff($questionCorrectAnswers, $providedAnswerIds));

			if (!$allCorrectAnswersProvided) {
				$score -= $questionPoints;
				$wrongAnswers += $questionPoints;
			}
		}

		$timeInMinutes = $this->changeTimeFormat($time);

		return $this->saveQuizResults($timeInMinutes, $score, $wrongAnswers, $arr);
	}

	public function changeTimeFormat(string $time): string
	{
		$timeInMinutes = 0;

		list($hours, $minutes) = explode(':', $time);

		$hours = intval($hours);
		$minutes = intval($minutes);

		if ($minutes >= 30) {
			$timeInMinutes = $hours + 1;
		} else {
			$timeInMinutes = $hours;
		}

		return $timeInMinutes;
	}

	public function saveQuizResults(int $timeInMinutes, int $score, int $wrongAnswers, array $arr): array
	{
		$userId = Auth::id();

		DB::table('quiz_user')->insert([
			'user_id'    => $userId,
			'quiz_id'    => $this->id,
			'time_taken' => $timeInMinutes,
			'score'      => $score,
		]);

		return [
			'title'           => $this->title,
			'time'            => $arr['time'],
			'correct_answers' => $score,
			'wrong_answers'   => $wrongAnswers,
		];
	}
}
