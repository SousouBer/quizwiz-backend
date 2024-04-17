<?php

namespace App\Nova;

use App\Models\DifficultyLevel as ModelsDifficultyLevel;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Color;
use Laravel\Nova\Fields\HasMany;

class DifficultyLevel extends Resource
{
	public static ModelsDifficultyLevel $model = \App\Models\DifficultyLevel::class;

	public static string $title = 'id';

	public static array $search = [
		'id',
	];

	public function fields(NovaRequest $request): array
	{
		return [
			ID::make()->sortable(),
			HasMany::make('Quizzes'),
			Text::make('title')->rules('required', 'string'),
			Color::make('color')->rules('required', 'string'),
			Color::make('background_color')->rules('required', 'string'),
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
