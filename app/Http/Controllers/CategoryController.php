<?php

namespace App\Http\Controllers;

use App\Http\Resources\CategoryResource;
use App\Models\Category;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class CategoryController
{
	public function index(): AnonymousResourceCollection
	{
		return CategoryResource::collection(Category::all());
	}
}
