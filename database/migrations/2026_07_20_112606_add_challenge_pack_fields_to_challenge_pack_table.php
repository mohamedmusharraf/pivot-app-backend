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
        Schema::table('challenge_pack', function (Blueprint $table) {
            $table->uuid('revenuecat_event_id')
                ->nullable()
                ->after('user_id');

            $table->unsignedInteger('total')
                ->default(0)
                ->after('product_id');

            $table->unsignedInteger('remaining')
                ->default(0)
                ->after('total');

            $table->enum('status', ['unused', 'used'])
                ->default('unused')
                ->after('remaining');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('challenge_pack', function (Blueprint $table) {
            $table->dropColumn([
                'revenuecat_event_id',
                'total',
                'remaining',
                'status',
            ]);
        });
    }
};