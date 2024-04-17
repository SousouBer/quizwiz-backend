<?php

namespace App\Nova;

use App\Models\Question as ModelsQuestion;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;

class Question extends Resource
{
	public static ModelsQuestion $model = \App\Models\Question::class;

	public static string $title = 'id';

	public static array $search = [
		'id',
	];

	public function fields(NovaRequest $request): array
	{
		return [
			ID::make()->sortable(),
			HasMany::make('Answer'),
			BelongsTo::make('Quiz'),
			Text::make('text')->rules('required', 'string'),
		];
	}

	public function cards(NovaRequest $request): array
	{
		return [];
	}

	public function filters(NovaRequest $request): array
	{
		return [];
	}

	public function lenses(NovaRequest $request): array
	{
		return [];
	}

	public function actions(NovaRequest $request): array
	{
		return [];
	}
}
