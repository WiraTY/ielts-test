<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Update existing placement tests to use GSE scale level mapping
        DB::table('placement_tests')->update([
            'level_mapping' => json_encode([
                "22-35" => "starter",              // GSE 22-35: Starter (A1-A1+)
                "30-42" => "elementary",           // GSE 30-42: Elementary (A1+-A2)
                "36-46" => "pre-intermediate",     // GSE 36-46: Pre-Intermediate (A2-B1-)
                "46-58" => "intermediate",         // GSE 46-58: Intermediate (B1)
                "57-67" => "upper-intermediate",   // GSE 57-67: Upper Intermediate (B2)
                "66-78" => "advanced"              // GSE 66-78: Advanced (C1)
            ])
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert to previous level mapping
        DB::table('placement_tests')->update([
            'level_mapping' => json_encode([
                "0-20" => "starter",
                "21-40" => "beginner",
                "41-60" => "elementary",
                "61-80" => "intermediate",
                "81-100" => "advanced"
            ])
        ]);
    }
};
