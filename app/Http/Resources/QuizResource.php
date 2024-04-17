<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\DB;

class QuizResource extends JsonResource
{
	public function toArray(Request $request): array
	{
		return [
			'id'               => $this->id,
			'title'            => $this->title,
			'categories'       => CategoryResource::collection($this->categories),
			'difficulty_level' => $this->unless($request->route('id'), DifficultyLevelResource::make($this->DifficultyLevel)),
			$this->mergeWhen($request->route('id'), [
				'instructions'    => $this->instructions,
				'questions'       => $this->questions->count(),
			]),
			'image'            => $this->image,
			'points'           => $this->answers()->where('is_correct', true)->count(),
			'time'             => $this->time,
			'plays'            => DB::table('quiz_user')
			->where('quiz_id', $this->id)
			->count(),
		];
	}
}
