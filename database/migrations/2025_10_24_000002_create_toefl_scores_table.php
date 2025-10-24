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
        Schema::create('toefl_scores', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->enum('test_type', ['practice', 'diagnostic', 'official']);
            $table->integer('reading_score')->default(0);
            $table->integer('listening_score')->default(0);
            $table->integer('speaking_score')->default(0);
            $table->integer('writing_score')->default(0);
            $table->integer('total_score')->virtualAs('reading_score + listening_score + speaking_score + writing_score');
            $table->timestamp('test_date')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'test_type', 'test_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('toefl_scores');
    }
};