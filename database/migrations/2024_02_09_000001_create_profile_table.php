<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('profiles', function (Blueprint $table) {
            $table->id();
            $table->string('name')->default('Gilbert Asare');
            $table->string('tagline')->nullable();
            $table->string('badge_text')->nullable();
            $table->text('bio')->nullable();
            $table->string('image_url')->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->json('contact_info')->nullable();
            $table->string('location')->nullable();
            $table->string('education_name')->nullable();
            $table->unsignedInteger('stat_projects')->default(0);
            $table->unsignedInteger('stat_clients')->default(0);
            $table->unsignedInteger('stat_years')->default(0);
            $table->unsignedInteger('stat_technologies')->default(0);
            $table->json('typing_phrases')->nullable();
            $table->json('social_links')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('profiles');
    }
};
