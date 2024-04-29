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
		$quizzesCount = Quiz::count();
		$categoriesCount = Category::count();

		$roundedQuizzesCount = ceil($quizzesCount / 5) * 5 - 5;
		$roundedCategoriesCount = ceil($categoriesCount / 5) * 5 - 5;

		return [
			'email'            => $this->email,
			'tel'              => $this->tel,
			'facebook'         => $this->facebook,
			'linkedin'         => $this->linkedin,
			'quizzes_count'    => $roundedQuizzesCount,
			'categories_count' => $roundedCategoriesCount,
		];
	}
}
