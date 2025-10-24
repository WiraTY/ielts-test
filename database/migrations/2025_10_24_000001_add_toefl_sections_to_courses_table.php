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
        Schema::table('courses', function (Blueprint $table) {
            $table->enum('toefl_section', ['reading', 'listening', 'speaking', 'writing', 'general'])->nullable()->after('level');
            $table->integer('target_score_min')->default(0)->after('toefl_section');
            $table->integer('target_score_max')->default(30)->after('target_score_min');
            $table->text('section_description')->nullable()->after('target_score_max');
            $table->boolean('is_toefl_practice')->default(false)->after('is_trial');
            $table->string('difficulty_level')->default('intermediate')->after('is_toefl_practice'); // easy, intermediate, advanced
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('courses', function (Blueprint $table) {
            $table->dropColumn([
                'toefl_section',
                'target_score_min',
                'target_score_max',
                'section_description',
                'is_toefl_practice',
                'difficulty_level'
            ]);
        });
    }
};