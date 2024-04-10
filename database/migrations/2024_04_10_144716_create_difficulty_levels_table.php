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
		Schema::create('difficulty_levels', function (Blueprint $table) {
			$table->id();
			$table->string('title');
			$table->string('color');
			$table->string('color_selected');
			$table->string('background_color');
			$table->string('background_color_selected');
			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 */
	public function down(): void
	{
		Schema::dropIfExists('difficulty_levels');
	}
};
