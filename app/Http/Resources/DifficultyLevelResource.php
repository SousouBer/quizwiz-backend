<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DifficultyLevelResource extends JsonResource
{
	public function toArray(Request $request): array
	{
		return [
			'id'                        => $this->id,
			'title'                     => $this->title,
			'color'                     => $this->color,
			'background_color'          => $this->background_color,
		];
	}
}
