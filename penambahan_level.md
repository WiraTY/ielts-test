# Penambahan Fitur Placement Test dan Leveling

## Overview
Menambahkan fitur placement test untuk menentukan level kemampuan bahasa Inggris siswa dan sistem leveling untuk course yang sesuai dengan level masing-masing siswa.

## Database Modifications

### 1. New Tables

#### a. placement_tests table
```php
Schema::create('placement_tests', function (Blueprint $table) {
    $table->id();
    $table->string('title');
    $table->text('description')->nullable();
    $table->integer('duration_minutes')->nullable();
    $table->boolean('is_active')->default(true);
    $table->json('level_mapping'); // JSON field to store score ranges and corresponding levels
    /*
    Example format:
    {
        "0-20": "starter",
        "21-40": "beginner",
        "41-60": "elementary",
        "61-80": "intermediate",
        "81-100": "advanced"
    }
    */
    $table->timestamps();
});
```

#### b. placement_test_questions table
```php
Schema::create('placement_test_questions', function (Blueprint $table) {
    $table->id();
    $table->foreignId('placement_test_id')->constrained()->onDelete('cascade');
    $table->text('question_text');
    $table->json('options'); // For MCQ - array of options
    $table->string('correct_answer'); // The correct option
    $table->integer('score')->default(1);
    $table->integer('order')->default(0);
    $table->timestamps();
});
```

#### c. placement_test_attempts table
```php
Schema::create('placement_test_attempts', function (Blueprint $table) {
    $table->id();
    $table->foreignId('placement_test_id')->constrained()->onDelete('cascade');
    $table->foreignId('user_id')->constrained()->onDelete('cascade');
    $table->timestamp('started_at')->nullable();
    $table->timestamp('finished_at')->nullable();
    $table->integer('score')->nullable();
    $table->string('assigned_level')->nullable(); // The level assigned based on score
    $table->string('status'); // 'in_progress', 'completed', 'timeout'
    $table->timestamps();
});
```

#### d. placement_test_answers table
```php
Schema::create('placement_test_answers', function (Blueprint $table) {
    $table->id();
    $table->foreignId('attempt_id')->constrained('placement_test_attempts')->onDelete('cascade');
    $table->foreignId('question_id')->constrained('placement_test_questions')->onDelete('cascade');
    $table->string('selected_answer'); // Student's selected option
    $table->boolean('is_correct')->nullable();
    $table->integer('score_awarded')->nullable();
    $table->timestamps();
});
```

### 2. Modifications to Existing Tables

#### a. courses table
```php
Schema::table('courses', function (Blueprint $table) {
    $table->string('level')->default('starter'); // starter, beginner, elementary, intermediate, advanced, etc.
});
```

#### b. users table
```php
Schema::table('users', function (Blueprint $table) {
    $table->boolean('has_taken_placement_test')->default(false);
    $table->string('assigned_level')->nullable(); // starter, beginner, elementary, intermediate, advanced, etc.
});
```

### 3. Existing Courses Leveling
All existing courses will need to be assigned appropriate levels:
- Current trial courses should be evaluated and assigned levels (starter, beginner, etc.)
- Admin interface should provide bulk editing capability to assign levels to existing courses
- Default level for existing courses will be 'starter' unless manually changed

### 4. Level Progression System
Users advance to higher levels by completing ALL courses at their current level:
- System automatically checks completion status when a course is finished
- When all courses at current level are completed, user advances to next level
- Next level courses become immediately accessible
- Users retain access to all previously unlocked levels (including lower levels)
- Progress tracking shows completion percentage for current level

## Admin Interface

### 1. Placement Test List Page
- Tabel daftar placement test dengan kolom: Title, Description, Jumlah soal, Status, Tanggal dibuat
- Tombol "Create New Placement Test"
- Filter dan search functionality

### 2. Create/Edit Placement Test Page
Form dengan bagian:
- Basic Information (Title, Description, Duration, Status)
- Level Mapping (Dynamic form untuk mendefinisikan range nilai dan level)
- Questions Management (Tabel soal, tombol Add Question, Import from Excel)

### 3. Excel Import Feature
- Modal import dengan drag & drop area
- Template download
- Progress indicator
- Validasi format file

### 4. Question Form Modal
- Question Text
- 4 Options (A, B, C, D)
- Correct Answer (radio buttons)
- Score

## Excel Import Specification

### Template Format:
- Column A: Question Text
- Column B: Option A
- Column C: Option B
- Column D: Option C
- Column E: Option D
- Column F: Correct Answer (A, B, C, or D)
- Column G: Score (optional, default to 1)

## Leveling System
- Multiple placement tests support
- Flexible level mapping based on score ranges
- Integration with existing course level system
- Students who skip placement test start at "starter" level

## Implementation Priority
See detailed task flow in `task_list.md` file, which organizes implementation in the following phases:

1. Database Structure
2. Models and Relationships
3. Admin Interface - Placement Test Management
4. Excel Import Functionality
5. Student Interface - Placement Test Taking
6. Level Assignment and Progression Logic
7. Course Access System Integration
8. Admin Features and Reporting
9. Testing and Quality Assurance
10. Documentation and Deployment