<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

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
}
