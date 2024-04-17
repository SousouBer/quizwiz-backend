<?php

namespace App\Nova;

use App\Models\User as ModelsUser;
use Illuminate\Validation\Rules;
use Laravel\Nova\Fields\BelongsToMany;
use Laravel\Nova\Fields\Gravatar;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Password;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;

class User extends Resource
{
	public static ModelsUser $model = \App\Models\User::class;

	public static string $title = 'name';

	public static array $search = [
		'id', 'name', 'email',
	];

	public function fields(NovaRequest $request): array
	{
		return [
			ID::make()->sortable(),
			BelongsToMany::make('Quizzes')->fields(function (NovaRequest $request, $relatedModel) {
				return [
					Number::make('time_taken')->min(1)->max(20)->step(1),
					Number::make('score')->min(1)->max(20)->step(1),
				];
			}),
			Gravatar::make()->maxWidth(50),

			Text::make('Username')
				->sortable()
				->rules('required', 'max:255'),

			Text::make('Email')
				->sortable()
				->rules('required', 'email', 'max:254')
				->creationRules('unique:users,email')
				->updateRules('unique:users,email,{{resourceId}}'),

			Password::make('Password')
				->onlyOnForms()
				->creationRules('required', Rules\Password::defaults())
				->updateRules('nullable', Rules\Password::defaults()),
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
