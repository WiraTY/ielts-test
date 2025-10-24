<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UpdateLessonsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Update existing lessons to set default values for new boolean fields
        DB::table('lessons')
            ->whereNull('has_audio')
            ->update([
                'has_audio' => DB::raw('CASE WHEN audio_path IS NOT NULL THEN 1 ELSE 0 END'),
                'has_speaking_practice' => DB::raw('CASE WHEN speaking_duration IS NOT NULL THEN 1 ELSE 0 END')
            ]);
    }
}
