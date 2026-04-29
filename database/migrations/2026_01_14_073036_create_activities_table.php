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
        Schema::create('activities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('hobby_id')->constrained()->cascadeOnDelete();
            $table->string('activity_title');
            $table->text('instruction')->nullable();
            $table->string('activity_type')->nullable();
            $table->string('subcategory')->nullable();
            $table->string('duration_minutes');
            $table->enum('tier', ['1', '2', '3']);
            $table->string('cost')->nullable();
            $table->string('location')->nullable();
            $table->string('age_range')->nullable();
            $table->integer('min_age')->nullable();
            $table->integer('max_age')->nullable();
            $table->boolean('neurodivergent_friendly')->default(false);
            $table->text('neurodivergent_notes')->nullable();
            $table->string('sensory_tags')->nullable();
            $table->string('social_type')->nullable();
            $table->enum('energy_level', ['Low', 'Medium', 'High']);
            $table->string('outcome_tag')->nullable();
            $table->json('mood_match')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('activities');
    }
};
