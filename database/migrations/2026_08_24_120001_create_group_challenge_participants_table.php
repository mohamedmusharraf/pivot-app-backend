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
        Schema::create('group_challenge_participants', function (Blueprint $table) {
            $table->id();

            $table->foreignId('session_id')->constrained('group_challenge_sessions')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();

            $table->enum('invite_status', ['invited', 'accepted', 'declined'])->default('invited');
            $table->timestamp('responded_at')->nullable();

            $table->unsignedInteger('progress')->default(0);
            $table->timestamp('completed_at')->nullable();

            $table->timestamps();

            $table->unique(['session_id', 'user_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('group_challenge_participants');
    }
};
