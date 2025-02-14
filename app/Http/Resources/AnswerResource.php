<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AnswerResource extends JsonResource
{
	public function toArray(Request $request): array
	{
		return [
			'id'         => $this->id,
			'text'       => $this->text,
			'is_correct' => $this->is_correct,
		];
	}
}
