<?php

namespace App\Http\Resources;

use App\Models\Category;
use App\Models\Quiz;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ContactResource extends JsonResource
{
	public function toArray(Request $request): array
	{
		$quizzesCount = Quiz::count() - 1;
		$categoriesCount = Category::count() - 1;

		return [
			'email'            => $this->email,
			'tel'              => $this->tel,
			'facebook'         => $this->facebook,
			'linkedin'         => $this->linkedin,
			'quizzes_count'    => $quizzesCount === -1 ? 0 : $quizzesCount,
			'categories_count' => $categoriesCount === -1 ? 0 : $categoriesCount,
		];
	}
}
