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
            $table->string('title');
            $table->text('description');
            $table->string('duration_minutes'); 
            $table->enum('energy_level', ['Easy', 'Intermediate', 'Advanced']);
            $table->string('age_suitability')->nullable();
            $table->enum('tier', ['Tier 1', 'Tier 2', 'Tier 3']);
            $table->boolean('neurodivergent_friendly')->default(true);
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
