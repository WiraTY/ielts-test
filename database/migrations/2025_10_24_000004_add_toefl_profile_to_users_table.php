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
        Schema::table('users', function (Blueprint $table) {
            $table->integer('toefl_target_score')->default(80)->after('current_level');
            $table->integer('toefl_latest_reading_score')->default(0)->after('toefl_target_score');
            $table->integer('toefl_latest_listening_score')->default(0)->after('toefl_latest_reading_score');
            $table->integer('toefl_latest_speaking_score')->default(0)->after('toefl_latest_listening_score');
            $table->integer('toefl_latest_writing_score')->default(0)->after('toefl_latest_speaking_score');
            $table->integer('toefl_latest_total_score')->virtualAs('toefl_latest_reading_score + toefl_latest_listening_score + toefl_latest_speaking_score + toefl_latest_writing_score')->after('toefl_latest_writing_score');
            $table->date('toefl_test_date')->nullable()->after('toefl_latest_total_score');
            $table->text('toefl_weak_areas')->nullable()->after('toefl_test_date'); // JSON array of weak sections
            $table->text('toefl_study_notes')->nullable()->after('toefl_weak_areas');
            $table->boolean('has_taken_toefl_diagnostic')->default(false)->after('toefl_study_notes');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'toefl_target_score',
                'toefl_latest_reading_score',
                'toefl_latest_listening_score',
                'toefl_latest_speaking_score',
                'toefl_latest_writing_score',
                'toefl_latest_total_score',
                'toefl_test_date',
                'toefl_weak_areas',
                'toefl_study_notes',
                'has_taken_toefl_diagnostic'
            ]);
        });
    }
};