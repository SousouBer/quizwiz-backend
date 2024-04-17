<?php

namespace App\Nova;

use App\Models\Quiz as ModelsQuiz;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\BelongsToMany;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Fields\Image;
use Laravel\Nova\Fields\Markdown;
use Laravel\Nova\Fields\Number;

class Quiz extends Resource
{
	public static ModelsQuiz $model = \App\Models\Quiz::class;

	public static string $title = 'id';

	public static array $search = [
		'id',
	];

	public function fields(NovaRequest $request): array
	{
		return [
			ID::make()->sortable(),
			BelongsTo::make('difficulty_level'),
			BelongsToMany::make('Categories'),
			BelongsToMany::make('Users')->nullable()->fields(function (NovaRequest $request, $relatedModel) {
				return [
					Number::make('time_taken')->rules('required', 'integer', 'min:1', 'max:20'),
					Number::make('score')->rules('required', 'integer', 'min:1', 'max:20'),
				];
			}),
			Text::make('title')->rules('required', 'string'),
			Image::make('image')->rules('required', 'image'),
			Markdown::make('description')->rules('required'),
			Text::make('instructions')->rules('required', 'string'),
			Number::make('points')->rules('required', 'integer', 'min:1', 'max:20'),
			Number::make('time')->rules('required', 'integer', 'min:1', 'max:20'),
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
