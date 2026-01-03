<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('favorites_movies', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
			$table->string('tmdb_id');
			$table->boolean('adult')->default(false);
			$table->string('original_language')->nullable();
			$table->string('original_title')->nullable();
			$table->string('title')->nullable();
			$table->longText('overview')->nullable();
			$table->string('backdrop_path')->nullable();
			$table->string('poster_path')->nullable();
			$table->string('release_date')->nullable();
			$table->float('popularity')->nullable();
			$table->float('vote_average')->nullable();
			$table->integer('vote_count')->nullable();
			$table->json('genres')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('favorites_movies');
    }
};
