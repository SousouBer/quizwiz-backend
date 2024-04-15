<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class QuizResource extends JsonResource
{
	public function toArray(Request $request): array
	{
		return [
			'id'               => $this->id,
			'title'            => $this->title,
			'categories'       => CategoryResource::collection($this->categories),
			'difficulty_level' => new DifficultyLevelResource($this->DifficultyLevel),
			'image'            => $this->image,
			'points'           => $this->points,
			'time'             => $this->time,
		];
	}
}
