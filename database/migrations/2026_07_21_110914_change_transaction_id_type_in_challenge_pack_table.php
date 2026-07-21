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
           DB::statement('ALTER TABLE challenge_pack ALTER COLUMN transaction_id TYPE VARCHAR(255)');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('challenge_pack', function (Blueprint $table) {
             DB::statement('ALTER TABLE challenge_pack ALTER COLUMN transaction_id TYPE UUID USING transaction_id::uuid');
        });
    }
};
