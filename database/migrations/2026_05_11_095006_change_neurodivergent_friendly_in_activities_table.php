<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement("ALTER TABLE activities ALTER COLUMN neurodivergent_friendly DROP DEFAULT");

        DB::statement("
            ALTER TABLE activities
            ALTER COLUMN neurodivergent_friendly TYPE VARCHAR(20)
            USING (
                CASE
                    WHEN neurodivergent_friendly IS TRUE THEN 'Yes'
                    ELSE 'No'
                END
            )
        ");

        DB::statement("ALTER TABLE activities DROP CONSTRAINT IF EXISTS activities_neurodivergent_friendly_check");
        DB::statement("
            ALTER TABLE activities
            ADD CONSTRAINT activities_neurodivergent_friendly_check
            CHECK (neurodivergent_friendly IN ('Yes', 'No', 'Partial'))
        ");

        DB::statement("ALTER TABLE activities ALTER COLUMN neurodivergent_friendly SET DEFAULT 'No'");
        DB::statement("ALTER TABLE activities ALTER COLUMN neurodivergent_friendly SET NOT NULL");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("ALTER TABLE activities ALTER COLUMN neurodivergent_friendly DROP DEFAULT");
        DB::statement("ALTER TABLE activities DROP CONSTRAINT IF EXISTS activities_neurodivergent_friendly_check");

        DB::statement("
            ALTER TABLE activities
            ALTER COLUMN neurodivergent_friendly TYPE BOOLEAN
            USING (
                CASE
                    WHEN neurodivergent_friendly = 'No' THEN FALSE
                    ELSE TRUE
                END
            )
        ");

        DB::statement("ALTER TABLE activities ALTER COLUMN neurodivergent_friendly SET DEFAULT FALSE");
        DB::statement("ALTER TABLE activities ALTER COLUMN neurodivergent_friendly SET NOT NULL");
    }
};
