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
        // Remove duplicate rows first, keeping the most recently updated one per user.
        $duplicateIds = DB::table('subscriptions as s1')
            ->join('subscriptions as s2', function ($join) {
                $join->on('s1.user_id', '=', 's2.user_id')
                    ->whereColumn('s1.id', '<', 's2.id');
            })
            ->pluck('s1.id')
            ->unique()
            ->all();

        if (! empty($duplicateIds)) {
            DB::table('subscriptions')->whereIn('id', $duplicateIds)->delete();
        }

        Schema::table('subscriptions', function (Blueprint $table) {
            $table->unique('user_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('subscriptions', function (Blueprint $table) {
            $table->dropUnique(['user_id']);
        });
    }
};
