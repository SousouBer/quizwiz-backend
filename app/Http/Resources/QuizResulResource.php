<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class QuizResulResource extends JsonResource
{
	public function toArray(Request $request): array
	{
		return [
			'title'           => $this['title'],
			'time'            => $this['time'],
			'correct_answers' => $this['correct_answers'],
			'wrong_answers'   => $this['wrong_answers'],
		];
	}
}
