<?php

namespace App\Nova;

use Laravel\Nova\Fields\ID;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Color;
use Laravel\Nova\Fields\HasMany;

class DifficultyLevel extends Resource
{
	public static $model = \App\Models\DifficultyLevel::class;

	public static $title = 'id';

	public static $search = [
		'id',
	];

	public function fields(NovaRequest $request): array
	{
		return [
			ID::make()->sortable(),
			HasMany::make('Quizzes'),
			Text::make('title'),
			Color::make('color'),
			Color::make('color_selected'),
			Color::make('background_color'),
			Color::make('background_color_selected'),
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
