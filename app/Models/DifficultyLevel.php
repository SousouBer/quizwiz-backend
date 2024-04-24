<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DifficultyLevel extends Model
{
	use HasFactory;

	protected $hidden = ['created_at', 'updated_at'];

	public function quizzes(): HasMany
	{
		return $this->hasMany(Quiz::class);
	}
}
