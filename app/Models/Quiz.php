<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
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

	public function scopeSearchFilter(Builder $query, string $search): Builder
	{
		return $query->where(function ($query) use ($search) {
			$query->where('title', 'like', '%' . $search . '%')
				  ->orWhereHas('categories', function ($query) use ($search) {
				  	$query->where('title', 'like', '%' . $search . '%');
				  });
		});
	}

	public function scopeCategoryFilter(Builder $query, string $categoryIDs): Builder
	{
		$categoryIdsArray = explode(',', $categoryIDs);

		return $query->whereHas('categories', function ($query) use ($categoryIdsArray) {
			$query->whereIn('categories.id', $categoryIdsArray);
		});
	}

	public function scopeLevelFilter(Builder $query, string $levelIDs): Builder
	{
		$levelIDsArray = explode(',', $levelIDs);
		return $query->whereIn('difficulty_level_id', $levelIDsArray);
	}

	public function scopeSort(Builder $query, string $sort): Builder
	{
		switch ($sort) {
			case 'asc':
				return $query->orderBy('title', 'asc');
				break;
			case 'desc':
				return $query->orderBy('title', 'desc');
				break;
			case 'oldest':
				return $query->oldest();
				break;
			case 'newest':
				return $query->latest();
				break;
			case 'popular':
				$query->select('quizzes.*', DB::raw('COUNT(quiz_user.quiz_id) as plays'))
				->leftJoin('quiz_user', 'quizzes.id', '=', 'quiz_user.quiz_id')
				->groupBy('quizzes.id');

				return $query->orderBy('plays', 'desc');
				break;
			default:
				return $query;
				break;
		}
	}

	public function scopeCompletedQuizzes(Builder $query): Builder
	{
		return $query->whereHas('users', function ($query) {
			$query->where('user_id', auth()->user()->id);
		});
	}

	public function scopeIncompletedQuizzes(Builder $query): Builder
	{
		return $query->whereDoesntHave('users', function ($query) {
			$query->where('user_id', auth()->user()->id);
		});
	}
}
