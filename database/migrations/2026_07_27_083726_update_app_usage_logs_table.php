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
        Schema::table('app_usage_logs', function (Blueprint $table) {
            $table->renameColumn(
                'usage_minutes',
                'duration_minutes'
            );

            $table->unsignedInteger('opened_count')
                ->default(0)
                ->after('duration_minutes');

            $table->index([
                'user_id',
                'started_at'
            ]);

            $table->index([
                'user_id',
                'package_name'
            ]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('app_usage_logs', function (Blueprint $table) {
            $table->dropIndex([
                'app_usage_logs_user_id_started_at_index'
            ]);

            $table->dropIndex([
                'app_usage_logs_user_id_package_name_index'
            ]);

            $table->dropColumn('opened_count');

            $table->renameColumn(
                'duration_minutes',
                'usage_minutes'
            );
        });
    }
};