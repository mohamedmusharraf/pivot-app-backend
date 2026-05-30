<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_daily_articles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('article_id')->constrained('research_articles')->cascadeOnDelete();
            $table->date('assigned_date');
            $table->timestamps();

            $table->unique(['user_id', 'assigned_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_daily_articles');
    }
};
