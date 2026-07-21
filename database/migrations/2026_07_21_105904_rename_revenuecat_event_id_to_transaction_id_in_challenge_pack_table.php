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
            $table->renameColumn('revenuecat_event_id', 'transaction_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('challenge_pack', function (Blueprint $table) {
            $table->renameColumn('transaction_id', 'revenuecat_event_id');
        });
    }
};
