<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class QuizzesRequest extends FormRequest
{
	public function rules(): array
	{
		return [
			'categories'            => 'nullable|string',
			'levels'                => 'nullable|string',
			'sort'                  => 'nullable|string',
			'search'                => 'nullable|string',
			'my_quizzes'            => 'nullable|boolean',
			'not_completed_quizzes' => 'nullable|boolean',
		];
	}

	public function prepareForValidation(): void
	{
		$this->merge([
			'my_quizzes'            => $this->boolean('my_quizzes'),
			'not_completed_quizzes' => $this->boolean('not_completed_quizzes'),
		]);
	}
}
