<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class QuizzesRequest extends FormRequest
{
	public function rules(): array
	{
		return [
			'categories' => 'nullable|string',
			'levels'     => 'nullable|string',
			'sort'       => 'nullable|string',
			'search'     => 'nullable|string',
		];
	}
}
