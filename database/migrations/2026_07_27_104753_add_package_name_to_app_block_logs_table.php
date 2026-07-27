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
        Schema::table('app_block_logs', function (Blueprint $table) {
            $table->string('package_name')
                ->nullable()
                ->after('app_name');

            $table->index([
                'user_id',
                'blocked_at'
            ]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('app_block_logs', function (Blueprint $table) {
            $table->dropIndex([
                'app_block_logs_user_id_blocked_at_index'
            ]);

            $table->dropColumn('package_name');
        });
    }
};