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
			'difficulty_level' => $this->unless($request->route()->getName() === 'quizzes.show', DifficultyLevelResource::make($this->DifficultyLevel)),
			$this->mergeWhen($request->route('quiz') && $request->route()->getName() !== 'quizzes.similar_quizzes', [
				'instructions'          => $this->instructions,
				'description'           => $this->description,
				'questions'             => $this->questions->count(),
				'questions_and_answers' => QuestionResource::collection($this->questions),
			]),
			'image'                 => str_starts_with($this->image, 'https') ? $this->image : asset('storage/' . $this->image),
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
			'created_at' => $this->created_at->format('Y-m-d H:i:s'),
		];
	}
}
