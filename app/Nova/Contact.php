<?php

namespace App\Nova;

use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;

class Contact extends Resource
{
	public static $model = \App\Models\Contact::class;

	public static $title = 'id';

	public static $search = [
		'id', 'email', 'telephone', 'facebook', 'linkedin',
	];

	public function fields(NovaRequest $request): array
	{
		return [
			ID::make()->sortable(),
			Text::make('email')->rules('required', 'string'),
			Text::make('telephone')->rules('required', 'string'),
			Text::make('facebook')->rules('required', 'string'),
			Text::make('linkedin')->rules('required', 'string'),
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
