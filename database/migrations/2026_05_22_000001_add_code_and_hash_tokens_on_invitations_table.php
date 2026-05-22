<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (! Schema::hasColumn('invitations', 'code')) {
            Schema::table('invitations', function (Blueprint $table) {
                $table->string('code', 6)->nullable()->after('token')->unique();
            });
        }

        $rows = DB::table('invitations')->select('id', 'token', 'code')->get();

        foreach ($rows as $row) {
            $updates = [];

            $token = (string) $row->token;
            $isSha256 = preg_match('/^[a-f0-9]{64}$/i', $token) === 1;
            if (! $isSha256) {
                $updates['token'] = hash('sha256', $token);
            }

            if (empty($row->code)) {
                do {
                    $code = strtoupper(Str::random(6));
                } while (DB::table('invitations')->where('code', $code)->exists());

                $updates['code'] = $code;
            }

            if ($updates !== []) {
                DB::table('invitations')
                    ->where('id', $row->id)
                    ->update($updates);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('invitations', 'code')) {
            Schema::table('invitations', function (Blueprint $table) {
                $table->dropUnique('invitations_code_unique');
                $table->dropColumn('code');
            });
        }
    }
};
