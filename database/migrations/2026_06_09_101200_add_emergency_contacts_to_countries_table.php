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
        Schema::table('countries', function (Blueprint $table) {
            $table->string('police')->nullable()->after('phone_code');
            $table->string('ambulance')->nullable()->after('police');
            $table->string('fire')->nullable()->after('ambulance');
            $table->text('notes')->nullable()->after('fire');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('countries', function (Blueprint $table) {
            $table->dropColumn(['police', 'ambulance', 'fire', 'notes']);
        });
    }
};
