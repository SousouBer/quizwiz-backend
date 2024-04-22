<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Config;

class CategorySeeder extends Seeder
{
	public function run(): void
	{
		$categories = Config::get('categories.categories');

		foreach ($categories as $category) {
			Category::create([
				'title' => $category,
			]);
		}
	}
}
