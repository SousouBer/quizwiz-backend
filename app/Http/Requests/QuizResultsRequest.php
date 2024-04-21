<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class QuizResultsRequest extends FormRequest
{
	public function rules(): array
	{
		return [
			'quiz_id' => 'required|exists:quizzes,id',
			'time'    => 'required|string',
			'answers' => 'required|array',
		];
	}
}
