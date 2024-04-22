<?php

namespace Database\Seeders;

use App\Models\DifficultyLevel;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Config;

class DifficultyLevelsSeeder extends Seeder
{
	public function run(): void
	{
		$difficultyLevels = Config::get('levels.difficulty_levels');

		foreach ($difficultyLevels as $difficultyLevel) {
			DifficultyLevel::create([
				'title'            => $difficultyLevel['title'],
				'color'            => $difficultyLevel['color'],
				'background_color' => $difficultyLevel['background_color'],
			]);
		}
	}
}
