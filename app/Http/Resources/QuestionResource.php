<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class QuestionResource extends JsonResource
{
	public function toArray(Request $request): array
	{
		return [
			'id'      => $this->id,
			'text'    => $this->text,
			'points'  => $this->answers()->where('is_correct', true)->count(),
			'answers' => AnswerResource::collection($this->answers),
		];
	}
}
