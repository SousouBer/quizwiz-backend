<?php

namespace App\Nova;

use App\Models\Answer as ModelsAnswer;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Fields\Boolean;

class Answer extends Resource
{
	public static ModelsAnswer $model = \App\Models\Answer::class;

	public static string $title = 'id';

	public static array $search = [
		'id',
	];

	public function fields(NovaRequest $request): array
	{
		return [
			ID::make()->sortable(),
			BelongsTo::make('Question'),
			Text::make('text')->rules('required', 'string'),
			Boolean::make('is_correct')->rules('required', 'boolean'),
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
