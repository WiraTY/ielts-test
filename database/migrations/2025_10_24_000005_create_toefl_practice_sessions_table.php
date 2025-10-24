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
        Schema::create('toefl_practice_sessions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('course_id')->constrained()->onDelete('cascade');
            $table->foreignId('lesson_id')->constrained()->onDelete('cascade');
            $table->enum('section', ['reading', 'listening', 'speaking', 'writing']);
            $table->enum('status', ['started', 'in_progress', 'completed', 'timeout'])->default('started');
            $table->timestamp('started_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->integer('time_spent_seconds')->default(0);
            $table->integer('score')->default(0);
            $table->integer('total_possible')->default(0);
            $table->decimal('accuracy_percentage', 5, 2)->default(0);
            $table->text('feedback')->nullable();
            $table->json('session_data')->nullable(); // Store session-specific data
            $table->timestamps();

            $table->index(['user_id', 'section', 'status']);
            $table->index(['user_id', 'completed_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('toefl_practice_sessions');
    }
};