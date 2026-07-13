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
        Schema::create('web_contents', function (Blueprint $table) {
            $table->id();
            $table->string('hero_image_url')->nullable();

            $table->string('story_title')->nullable();
            $table->string('story_subtitle')->nullable();
            $table->text('story_content')->nullable();
            $table->string('philosophy_title')->nullable();
            $table->string('philosophy_subtitle')->nullable();
            $table->text('philosophy_content')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('web_contents');
    }
};
