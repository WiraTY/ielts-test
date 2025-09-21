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
        Schema::table('lessons', function (Blueprint $table) {
            $table->dropColumn([
                'audio_path',
                'listening_description',
                'speaking_description',
                'speaking_duration',
                'has_audio',
                'has_speaking_practice'
            ]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('lessons', function (Blueprint $table) {
            $table->string('audio_path')->nullable();
            $table->text('listening_description')->nullable();
            $table->text('speaking_description')->nullable();
            $table->integer('speaking_duration')->nullable();
            $table->boolean('has_audio')->default(false);
            $table->boolean('has_speaking_practice')->default(false);
        });
    }
};
