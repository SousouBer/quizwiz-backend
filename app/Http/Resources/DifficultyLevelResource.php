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
			'color_selected'            => $this->color_selected,
			'background_color'          => $this->background_color,
			'background_color_selected' => $this->background_color_selected,
		];
	}
}
