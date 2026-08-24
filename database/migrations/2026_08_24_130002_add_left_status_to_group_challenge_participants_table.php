<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement('ALTER TABLE group_challenge_participants DROP CONSTRAINT group_challenge_participants_invite_status_check');
        DB::statement("ALTER TABLE group_challenge_participants ADD CONSTRAINT group_challenge_participants_invite_status_check CHECK (invite_status IN ('invited', 'accepted', 'declined', 'left'))");

        Schema::table('group_challenge_participants', function (Blueprint $table) {
            $table->timestamp('left_at')->nullable()->after('responded_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('group_challenge_participants', function (Blueprint $table) {
            $table->dropColumn('left_at');
        });

        DB::statement('ALTER TABLE group_challenge_participants DROP CONSTRAINT group_challenge_participants_invite_status_check');
        DB::statement("ALTER TABLE group_challenge_participants ADD CONSTRAINT group_challenge_participants_invite_status_check CHECK (invite_status IN ('invited', 'accepted', 'declined'))");
    }
};
