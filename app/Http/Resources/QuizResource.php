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
			'difficulty_level' => $this->unless($request->route('quiz'), DifficultyLevelResource::make($this->DifficultyLevel)),
			$this->mergeWhen($request->route('quiz'), [
				'instructions'          => $this->instructions,
				'questions'             => $this->questions->count(),
				'questions_and_answers' => QuestionResource::collection($this->questions),
			]),
			'image'                 => $this->image,
			'points'                => $this->answers()->where('is_correct', true)->count(),
			'time'                  => $this->time,
			'plays'                 => DB::table('quiz_user')
			->where('quiz_id', $this->id)
			->count(),
			$this->mergeWhen($request->user() !== null && $this->users->contains($request->user()), function () use ($request) {
				$user = $this->users->firstWhere('id', $request->user()->id);

				if ($user) {
					return ['results' => $user->pivot];
				}
			}),
		];
	}
}
