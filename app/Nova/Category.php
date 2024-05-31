<?php

namespace App\Nova;

use Laravel\Nova\Fields\BelongsToMany;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;

class Category extends Resource
{
	public static $model = \App\Models\Category::class;

	public static $title = 'title';

	public static $search = [
		'id', 'title',
	];

	public function fields(NovaRequest $request): array
	{
		return [
			ID::make()->sortable(),
			BelongsToMany::make('Quizzes'),
			Text::make('title')->rules('required', 'string'),
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
