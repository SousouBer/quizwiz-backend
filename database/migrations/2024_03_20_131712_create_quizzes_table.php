<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
	/**
	 * Run the migrations.
	 */
	public function up(): void
	{
		Schema::create('quizzes', function (Blueprint $table) {
			$table->id();
			$table->foreignId('category_id')->constrained()->cascadeOnDelete();
			$table->foreignId('difficulty_level_id');
			$table->string('title');
			$table->string('image');
			$table->string('description');
			$table->string('instructions');
			$table->tinyInteger('points')->unsigned();
			$table->tinyInteger('time')->unsigned();
			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 */
	public function down(): void
	{
		Schema::dropIfExists('quizzes');
	}
};
