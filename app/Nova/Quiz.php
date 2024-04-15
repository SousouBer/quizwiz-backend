<?php

namespace App\Nova;

use App\Models\User;
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
	public static $model = \App\Models\Quiz::class;

	public static $title = 'id';

	public static $search = [
		'id',
	];

	public function fields(NovaRequest $request): array
	{
		return [
			ID::make()->sortable(),
			BelongsTo::make('difficulty_level'),
			BelongsToMany::make('Categories'),
			BelongsToMany::make('Users')->fields(function (NovaRequest $request, User $relatedModel) {
				return [
					Number::make('time_taken')->min(1)->max(20)->step(1),
					Number::make('score')->min(1)->max(20)->step(1),
				];
			}),
			Text::make('title'),
			Image::make('image'),
			Markdown::make('description'),
			Text::make('instructions'),
			Number::make('points')->min(1)->max(20)->step(1),
			Number::make('time')->min(1)->max(20)->step(1),
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
