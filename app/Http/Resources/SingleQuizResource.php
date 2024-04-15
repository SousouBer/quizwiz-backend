<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SingleQuizResource extends JsonResource
{
	public function toArray(Request $request): array
	{
		return [
			'id'              => $this->id,
			'title'           => $this->title,
			'categories'      => CategoryResource::collection($this->categories),
			'questions'       => $this->questions->count(),
			'points'          => $this->answers()->where('is_correct', true)->count(),
			'plays'           => $this->users()->count(),
			'time'            => $this->time,
			'instructions'    => $this->instructions,
		];
	}
}
