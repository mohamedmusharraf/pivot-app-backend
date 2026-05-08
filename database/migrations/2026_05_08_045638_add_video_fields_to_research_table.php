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
        Schema::table('research_articles', function (Blueprint $table) {
            $table->string('video_link')->nullable()->after('summary');

            $table->enum('video_type', [
                'fun_facts',
                'summary',
                'both',
            ])->nullable()->after('video_link');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('research_articles', function (Blueprint $table) {

            $table->dropColumn([
                'video_link',
                'video_type',
            ]);
        });
    }
};
