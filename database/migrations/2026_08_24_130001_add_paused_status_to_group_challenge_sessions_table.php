<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement('ALTER TABLE group_challenge_sessions DROP CONSTRAINT group_challenge_sessions_status_check');
        DB::statement("ALTER TABLE group_challenge_sessions ADD CONSTRAINT group_challenge_sessions_status_check CHECK (status IN ('pending', 'in_progress', 'paused', 'completed', 'cancelled'))");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement('ALTER TABLE group_challenge_sessions DROP CONSTRAINT group_challenge_sessions_status_check');
        DB::statement("ALTER TABLE group_challenge_sessions ADD CONSTRAINT group_challenge_sessions_status_check CHECK (status IN ('pending', 'in_progress', 'completed', 'cancelled'))");
    }
};
