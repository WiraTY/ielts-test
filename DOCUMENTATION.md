# TOEFL Test Management System Documentation

## Table of Contents
1. [Overview](#overview)
2. [Features](#features)
3. [Technology Stack](#technology-stack)
4. [System Architecture](#system-architecture)
5. [Database Schema](#database-schema)
6. [Installation](#installation)
7. [Usage](#usage)
8. [User Roles and Permissions](#user-roles-and-permissions)
9. [API Endpoints](#api-endpoints)
10. [Livewire Components](#livewire-components)
11. [Development](#development)
12. [Testing](#testing)
13. [Deployment](#deployment)
14. [Troubleshooting](#troubleshooting)
15. [Recent Updates and Changes](#recent-updates-and-changes)
16. [TOEFL Diagnostic and Assessment System](#toefl-diagnostic-and-assessment-system)

## Overview

The TOEFL Test Management System is a comprehensive Laravel + Livewire platform designed specifically for TOEFL (Test of English as a Foreign Language) preparation and assessment. The system provides students with targeted practice across all four TOEFL sections (Reading, Listening, Speaking, Writing), includes a sophisticated diagnostic test system, and offers administrators powerful tools for content management and student progress tracking.

## Features

### For Students:
- User authentication (registration/login)
- **TOEFL Section-Specific Courses**: Targeted practice for Reading, Listening, Speaking, and Writing sections
- **Comprehensive TOEFL Diagnostic Test**: Initial assessment to identify strengths and weaknesses
- **Personalized Learning Paths**: Course recommendations based on diagnostic results and performance
- **Section-Wise Progress Tracking**: Detailed progress monitoring for each TOEFL section
- **Score Tracking & Analytics**: Track improvement over time with detailed performance metrics
- **Target Score Setting**: Set and monitor progress toward specific TOEFL score goals
- **Advanced Question Types**: Full support for all TOEFL question formats
  - Reading: Factual information, inference, vocabulary, prose summary, sentence insertion
  - Listening: Gist questions, detail questions, attitude, organization, inference
  - Speaking: Independent tasks and integrated speaking tasks with recording capabilities
  - Writing: Integrated writing tasks and independent essays
- **Weak Area Identification**: Automatic detection of areas needing improvement
- **Study Recommendations**: Personalized study plans based on performance data
- **Practice Sessions**: Timed practice with immediate feedback and scoring

### For Administrators:
- **TOEFL Course Management**: Create and manage section-specific courses with difficulty levels
- **Diagnostic Test Management**: Create comprehensive TOEFL diagnostic assessments
- **Advanced Question Management**: Support for all TOEFL question types with rubrics and sample answers
- **User Progress Analytics**: Comprehensive reporting on student performance across all sections
- **Score Tracking Dashboard**: Monitor student improvement and identify trends
- **Content Organization**: Organize courses by section, difficulty, and target score ranges
- **Automatic Weak Area Detection**: System identifies and highlights student weaknesses
- **Personalized Recommendations Management**: Configure recommendation algorithms
- **Performance Reporting**: Detailed analytics for individual students and groups
- **TOEFL Score Band Management**: Track student progress through score bands (0-120 scale)
- **Diagnostic Test Analysis**: In-depth analysis of diagnostic test results
- **Content Curation**: Curate learning paths based on performance data

### Technical Features:
- Responsive design with TailwindCSS
- Video embedding support
- WYSIWYG content editor (TinyMCE)
- Progress tracking
- Quiz timer functionality
- Results calculation and display
- View switching between admin and student perspectives
- Course thumbnail management with recommended size 400px x 200px (2:1 ratio)
- Audio and speaking practice functionality
- Level-based course access control
- Enhanced UI/UX with improved button styling and layouts

## Technology Stack

- **Backend**: Laravel 12.x (PHP 8.2+)
- **Frontend**: Blade templates, Livewire 3.x, TailwindCSS
- **Database**: MySQL/SQLite
- **Authentication**: Laravel Breeze
- **Rich Text Editor**: TinyMCE
- **Asset Compilation**: Vite
- **Testing**: PHPUnit
- **UI Enhancements**: SweetAlert2

## System Architecture

The application follows a typical Laravel MVC architecture with Livewire components for interactive frontend features:

```
app/
├── Http/
│   ├── Controllers/
│   │   ├── Admin/          # Admin controllers for CRUD operations
│   │   └── Student/        # Student dashboard controller
│   └── Livewire/           # Livewire components
├── Models/                 # Eloquent models
├── Livewire/               # Livewire component classes
└── Policies/               # Authorization policies

resources/
├── views/
│   ├── admin/              # Admin panel views
│   ├── student/            # Student dashboard views
│   ├── courses/            # Course listing/detail views
│   ├── lessons/            # Lesson views
│   ├── quizzes/            # Quiz views
│   ├── placement-tests/    # Placement test views
│   ├── student/recordings/ # Student recordings views
│   ├── layouts/            # Base layouts
│   └── components/         # Reusable Blade components
└── js/                     # JavaScript assets

routes/
├── web.php                 # Web routes
└── api.php                 # API routes (if any)

database/
├── migrations/             # Database schema migrations
└── seeders/                # Database seeders
```

## Database Schema

### Users
- id (bigint, primary)
- name (string)
- email (string, unique)
- password (string)
- role (string) - 'admin', 'student'
- avatar (string, nullable)
- email_verified_at (timestamp, nullable)
- has_taken_placement_test (boolean, default: false)
- assigned_level (string, nullable) - starter, beginner, elementary, intermediate, advanced
- current_level (string, nullable) - starter, beginner, elementary, intermediate, advanced
- unlocked_levels (json, nullable) - array of levels unlocked by the user
- **TOEFL-Specific Fields:**
- toefl_target_score (integer, default: 80) - Target TOEFL score
- toefl_latest_reading_score (integer, default: 0) - Latest reading section score
- toefl_latest_listening_score (integer, default: 0) - Latest listening section score
- toefl_latest_speaking_score (integer, default: 0) - Latest speaking section score
- toefl_latest_writing_score (integer, default: 0) - Latest writing section score
- toefl_latest_total_score (integer, virtual: sum of latest section scores)
- toefl_test_date (date, nullable) - Date of latest TOEFL test
- toefl_weak_areas (json, nullable) - Array of weak sections
- toefl_study_notes (text, nullable) - Student's study notes
- has_taken_toefl_diagnostic (boolean, default: false) - Whether user completed diagnostic
- created_at, updated_at (timestamps)

### Courses
- id (bigint, primary)
- slug (string, unique)
- title (string)
- description (text, nullable)
- thumbnail_path (string, nullable) - Stores path to thumbnail image. Recommended size: 400px x 200px (2:1 ratio) for optimal display in course listings.
- is_trial (boolean, default: true)
- level (string, default: 'starter') - starter, beginner, elementary, intermediate, advanced
- created_by (foreign key to users.id)
- published_at (timestamp, nullable)
- order (integer, default: 0)
- **TOEFL-Specific Fields:**
- toefl_section (enum: 'reading', 'listening', 'speaking', 'writing', 'general', nullable) - TOEFL section
- target_score_min (integer, default: 0) - Minimum score for course access
- target_score_max (integer, default: 30) - Maximum score for course benefit
- section_description (text, nullable) - Detailed section-specific description
- is_toefl_practice (boolean, default: false) - Whether this is a TOEFL practice course
- difficulty_level (string, default: 'intermediate') - easy, intermediate, advanced
- created_at, updated_at (timestamps)

### Lessons
- id (bigint, primary)
- course_id (foreign key to courses.id)
- slug (string, unique)
- title (string)
- content (text, nullable)
- video_url (string, nullable)
- order (integer, default: 0)
- duration (integer, seconds, default: 0)
- created_at, updated_at (timestamps)

### Quizzes
- id (bigint, primary)
- lesson_id (foreign key to lessons.id)
- title (string)
- duration_minutes (integer)
- pass_score (integer)
- created_at, updated_at (timestamps)

### Questions
- id (bigint, primary)
- quiz_id (foreign key to quizzes.id)
- type (string) - 'mcq', 'multi', 'essay'
- question_text (text)
- options (json, nullable)
- answer_key (json, nullable)
- score (integer, default: 1)
- **TOEFL-Specific Fields:**
- toefl_section (enum: 'reading', 'listening', 'speaking', 'writing', nullable) - TOEFL section
- toefl_question_type (enum: Various TOEFL question types, nullable) - Specific TOEFL question format
- time_limit_seconds (integer, nullable) - Time limit for answering
- preparation_time_notes (text, nullable) - Preparation time instructions
- scoring_rubric (json, nullable) - Evaluation rubric for speaking/writing
- sample_answer (json, nullable) - Sample answer for reference
- created_at, updated_at (timestamps)

**TOEFL Question Types:**
- **Reading:** reading_factual_information, reading_negative_factual_information, reading_inference, reading_rhetorical_purpose, reading_vocabulary, reading_reference, reading_sentence_insertion, reading_prose_summary, reading_fill_in_table, reading_complete_summary
- **Listening:** listening_gist_content, listening_gist_purpose, listening_detail, listening_function, listening_attitude, listening_organization, listening_connecting_content, listening_inference
- **Speaking:** speaking_independent_personal_preference, speaking_independent_choice, speaking_integrated_campus_situation, speaking_integrated_academic_course, speaking_integrated_reading_listening
- **Writing:** writing_integrated_reading_listening, writing_independent_essay

### QuizAttempts
- id (bigint, primary)
- quiz_id (foreign key to quizzes.id)
- user_id (foreign key to users.id)
- started_at (timestamp)
- finished_at (timestamp, nullable)
- score (integer)
- status (string) - 'in_progress', 'completed', 'timeout'
- metadata (json, nullable)
- created_at, updated_at (timestamps)

### QuizAnswers
- id (bigint, primary)
- attempt_id (foreign key to quiz_attempts.id)
- question_id (foreign key to questions.id)
- answer (json/text)
- is_correct (boolean)
- score_awarded (integer)
- created_at, updated_at (timestamps)

### Progress
- id (bigint, primary)
- user_id (foreign key to users.id)
- lesson_id (foreign key to lessons.id)
- status (string) - 'not_started', 'in_progress', 'completed'
- completed_at (timestamp, nullable)
- created_at, updated_at (timestamps)

### Placement Tests
- id (bigint, primary)
- title (string)
- description (text, nullable)
- duration_minutes (integer, nullable)
- is_active (boolean, default: true)
- level_mapping (json) - Maps score ranges to levels (e.g., {"0-20": "starter", "21-40": "beginner"})
- created_at, updated_at (timestamps)

### Placement Test Questions
- id (bigint, primary)
- placement_test_id (foreign key to placement_tests.id)
- question_text (text)
- options (json) - Array of options for multiple choice questions
- correct_answer (string) - The correct option
- score (integer, default: 1)
- order (integer, default: 0)
- created_at, updated_at (timestamps)

### Placement Test Attempts
- id (bigint, primary)
- placement_test_id (foreign key to placement_tests.id)
- user_id (foreign key to users.id)
- started_at (timestamp, nullable)
- finished_at (timestamp, nullable)
- score (integer, nullable)
- assigned_level (string, nullable) - The level assigned based on score
- status (string) - 'in_progress', 'completed', 'timeout'
- created_at, updated_at (timestamps)

### Placement Test Answers
- id (bigint, primary)
- attempt_id (foreign key to placement_test_attempts.id)
- question_id (foreign key to placement_test_questions.id)
- selected_answer (string) - Student's selected option
- is_correct (boolean, nullable)
- score_awarded (integer, nullable)
- created_at, updated_at (timestamps)

### Lesson Audio
- id (bigint, primary)
- lesson_id (foreign key to lessons.id)
- description (text, nullable)
- audio_file_path (string, nullable)
- enable (boolean, default: false)
- created_at, updated_at (timestamps)

### Lesson Speaking
- id (bigint, primary)
- lesson_id (foreign key to lessons.id)
- description (text, nullable)
- duration_seconds (integer, default: 60)
- enable (boolean, default: false)
- created_at, updated_at (timestamps)

### Student Recordings
- id (bigint, primary)
- user_id (foreign key to users.id)
- lesson_id (foreign key to lessons.id)
- file_path (string)
- created_at, updated_at (timestamps)

### TOEFL Scores
- id (bigint, primary)
- user_id (foreign key to users.id)
- test_type (enum: 'practice', 'diagnostic', 'official')
- reading_score (integer, default: 0)
- listening_score (integer, default: 0)
- speaking_score (integer, default: 0)
- writing_score (integer, default: 0)
- total_score (integer, virtual: sum of section scores)
- test_date (timestamp, nullable)
- notes (text, nullable)
- created_at, updated_at (timestamps)

### TOEFL Practice Sessions
- id (bigint, primary)
- user_id (foreign key to users.id)
- course_id (foreign key to courses.id)
- lesson_id (foreign key to lessons.id)
- section (enum: 'reading', 'listening', 'speaking', 'writing')
- status (enum: 'started', 'in_progress', 'completed', 'timeout')
- started_at (timestamp, nullable)
- completed_at (timestamp, nullable)
- time_spent_seconds (integer, default: 0)
- score (integer, default: 0)
- total_possible (integer, default: 0)
- accuracy_percentage (decimal, default: 0.00)
- feedback (text, nullable)
- session_data (json, nullable)
- created_at, updated_at (timestamps)

### TOEFL Diagnostic Tests
- id (bigint, primary)
- title (string)
- description (text, nullable)
- duration_minutes (integer, default: 120)
- is_active (boolean, default: true)
- section_weights (json, nullable) - Weight distribution for each section
- score_ranges (json, nullable) - Score range mappings for performance levels
- recommendations (text, nullable)
- created_at, updated_at (timestamps)

### TOEFL Diagnostic Questions
- id (bigint, primary)
- toefl_diagnostic_test_id (foreign key to toefl_diagnostic_tests.id)
- section (enum: 'reading', 'listening', 'speaking', 'writing')
- question_text (text)
- options (json, nullable) - For multiple choice questions
- correct_answer (string, nullable) - Correct answer for multiple choice
- score (integer, default: 1)
- order (integer, default: 0)
- explanation (text, nullable)
- rubric (json, nullable) - Scoring rubric for speaking/writing
- created_at, updated_at (timestamps)

### TOEFL Diagnostic Attempts
- id (bigint, primary)
- toefl_diagnostic_test_id (foreign key to toefl_diagnostic_tests.id)
- user_id (foreign key to users.id)
- started_at (timestamp, nullable)
- finished_at (timestamp, nullable)
- reading_score (integer, default: 0)
- listening_score (integer, default: 0)
- speaking_score (integer, default: 0)
- writing_score (integer, default: 0)
- total_score (integer, virtual: sum of section scores)
- status (enum: 'in_progress', 'completed', 'timeout')
- section_feedback (json, nullable)
- overall_feedback (text, nullable)
- recommendations (json, nullable)
- created_at, updated_at (timestamps)

### TOEFL Diagnostic Answers
- id (bigint, primary)
- attempt_id (foreign key to toefl_diagnostic_attempts.id)
- question_id (foreign key to toefl_diagnostic_questions.id)
- answer (text)
- is_correct (boolean, nullable)
- score_awarded (integer, default: 0)
- feedback (text, nullable)
- created_at, updated_at (timestamps)

## Installation

### Prerequisites
- PHP 8.2+
- Composer
- Node.js & NPM
- MySQL or SQLite database

### Steps

1. Clone the repository:
   ```bash
   git clone <repository-url>
   cd trial-class-app
   ```

2. Install PHP dependencies:
   ```bash
   composer install
   ```

3. Install Node dependencies:
   ```bash
   npm install
   ```

4. Copy and configure the environment file:
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

5. Configure your database in the `.env` file

6. Run database migrations and seeders:
   ```bash
   php artisan migrate
   php artisan db:seed
   ```

7. Build frontend assets:
   ```bash
   npm run build
   ```

8. Start the development server:
   ```bash
   php artisan serve
   ```

## Usage

### For Students
1. Register as a new user or login with existing credentials
2. Take the placement test to determine your English proficiency level
3. Browse courses from the course catalog (all courses visible but locked courses indicated)
4. Enroll in courses at your current level
5. Access lessons to view content, videos, and complete audio/speaking practice
6. Take quizzes after completing lessons
7. View results and track progress on the dashboard
8. Complete all courses at your current level to advance to the next level
9. Access and manage speaking practice recordings with inline playback and deletion

### For Administrators
1. Login with admin credentials
2. Access the admin panel at `/admin/dashboard`
3. Manage courses, lessons, quizzes, and questions using the CRUD interfaces
4. Create and manage placement tests with Excel import functionality
5. Monitor student progress and quiz results
6. Upload content and manage users
7. Assign levels to courses and users
8. View detailed reports and analytics
9. Switch to student view using "View as Student" button to preview student experience

### Default Credentials
- Admin: 
  - Email: admin@example.com
  - Password: password
- Student (test user):
  - Email: test@example.com
  - Password: password

## User Roles and Permissions

### Administrator
Can perform all management functions:
- Create, read, update, delete courses
- Manage lessons within courses
- Create and edit quizzes and questions
- View student progress and results
- Manage user accounts
- Create and manage placement tests
- Assign levels to courses and users
- View detailed reports and analytics
- Switch between admin and student views

### Student
Can access learning content:
- Take placement test to determine level
- View all courses in catalog (locked courses indicated)
- Access lessons and content at their assigned level
- Complete audio listening and speaking practice exercises
- Take quizzes
- View personal progress and results
- Record and playback speaking practice with inline controls
- Delete recordings with confirmation dialogs
- Advance through levels by completing courses

### Guest
Limited access:
- View course catalog (starter level courses only)
- Register for an account

## API Endpoints

Currently, the application primarily uses Livewire for interactivity rather than a REST API. However, there are some backend endpoints for file uploads and audio recording:

### Admin Endpoints
- `POST /admin/questions/upload-image` - Upload images for quiz questions
- `POST /admin/lessons/upload-image` - Upload images for lesson content
- `POST /admin/placement-tests/{placementTest}/import-questions` - Import questions from Excel file
- `GET /admin/placement-tests/download-template` - Download Excel template for question import
- `GET /admin/courses/bulk-assign-level` - Show bulk course level assignment interface
- `POST /admin/courses/bulk-assign-level` - Assign levels to multiple courses
- `GET /admin/placement-tests/reports` - View placement test reports
- `GET /admin/reports/level-progression` - View level progression reports
- `GET /admin/user-level-tracking` - View user level tracking dashboard

### Student Endpoints
- `POST /audio/store` - Store audio recordings for speaking practice
- `DELETE /audio/{recording}` - Delete audio recordings

### Authentication Endpoints
- Standard Laravel authentication routes are used for login, registration, password reset, etc.

## Livewire Components

### Frontend Components
- `CourseList` - Displays list of available courses (with lock indicators for inaccessible courses)
- `CourseDetail` - Shows course details and lessons (with lock indicators for inaccessible courses)
- `LessonViewer` - Renders lesson content and tracks progress
- `VideoPlayer` - Wrapper for video embedding
- `QuizRunner` - Handles quiz taking with timer and navigation
- `QuizResult` - Displays quiz results and feedback
- `PlacementTestRunner` - Handles placement test taking with timer and navigation
- `PlacementTestResult` - Displays placement test results and assigned level
- `AudioPlayer` - Audio playback component for lesson audio content
- `AudioRecorder` - Speaking practice recording component

### Admin Components
- `Admin\CourseForm` - CRUD interface for courses
- `Admin\LessonForm` - CRUD interface for lessons
- `Admin\QuizForm` - CRUD interface for quizzes and questions
- `Admin\UserList` - User management interface
- `Admin\ReportList` - Reporting interface
- `Admin\PlacementTestForm` - CRUD interface for placement tests
- `Admin\PlacementTestQuestionForm` - CRUD interface for placement test questions

## Development

### Project Structure
The application follows Laravel conventions with additional organization for Livewire components:

```
app/
├── Http/
│   ├── Controllers/
│   │   ├── Admin/          # Admin CRUD controllers
│   │   └── Student/        # Student dashboard controller
│   └── Livewire/           # Livewire component classes
├── Models/                 # Eloquent models
├── Livewire/               # Livewire component classes
└── Policies/               # Authorization policies

resources/
├── views/
│   ├── admin/              # Admin panel views
│   ├── student/            # Student dashboard views
│   ├── courses/            # Course views
│   ├── lessons/            # Lesson views
│   ├── quizzes/            # Quiz views
│   ├── placement-tests/    # Placement test views
│   ├── student/recordings/ # Student recordings views
│   ├── layouts/            # Base layouts
│   └── components/         # Reusable Blade components
└── js/                     # JavaScript assets
```

### Key Models and Relationships
- `User` - Can be admin or student, has levels and progress
- `Course` - Contains multiple lessons, created by a user, assigned a level
- `Lesson` - Belongs to a course, can have one quiz, audio, and speaking practice
- `Quiz` - Belongs to a lesson, contains multiple questions
- `Question` - Belongs to a quiz
- `QuizAttempt` - Tracks a user's attempt at a quiz
- `QuizAnswer` - Stores answers for a quiz attempt
- `Progress` - Tracks user progress through lessons
- `PlacementTest` - Contains multiple placement test questions
- `PlacementTestQuestion` - Belongs to a placement test
- `PlacementTestAttempt` - Tracks a user's attempt at a placement test
- `PlacementTestAnswer` - Stores answers for a placement test attempt
- `LessonAudio` - Belongs to a lesson, contains audio file information
- `LessonSpeaking` - Belongs to a lesson, contains speaking practice information
- `StudentRecording` - Tracks student audio recordings

### Creating New Features

1. **New Model**:
   ```bash
   php artisan make:model ModelName -m
   ```
   Add relationships and fillable properties

2. **New Livewire Component**:
   ```bash
   php artisan make:livewire ComponentName
   ```
   Implement mount() and render() methods

3. **New Controller**:
   ```bash
   php artisan make:controller ControllerName
   ```
   Add resource methods if needed

4. **New Migration**:
   ```bash
   php artisan make:migration create_table_name_table
   ```
   Define schema changes

### Styling
The application uses TailwindCSS for styling. Custom configurations can be found in:
- `tailwind.config.js`
- `postcss.config.js`

## Testing

### Running Tests
```bash
php artisan test
```

### Test Structure
Tests are organized in:
- `tests/Unit` - Model and class unit tests
- `tests/Feature` - Feature and integration tests

### Writing Tests
1. Create test files using:
   ```bash
   php artisan make:test TestName
   ```

2. Use Laravel's testing helpers for HTTP requests, database assertions, etc.

## Deployment

### Server Requirements
- PHP 8.2+
- MySQL 5.7+ or PostgreSQL 9.6+
- Composer
- Node.js & NPM
- Web server (Apache/Nginx)

### Deployment Steps
1. Clone repository to server
2. Install dependencies:
   ```bash
   composer install --no-dev
   npm install
   npm run build
   ```
3. Set proper file permissions:
   ```bash
   chmod -R 755 storage bootstrap/cache
   ```
4. Configure environment variables in `.env`
5. Run migrations:
   ```bash
   php artisan migrate --force
   ```
6. Configure web server to point to `public/` directory

### Environment Configuration
Key environment variables:
- `APP_ENV` - local/production
- `APP_KEY` - Application encryption key
- `DB_CONNECTION` - Database connection
- `DB_HOST`, `DB_PORT`, `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD` - Database credentials

## Troubleshooting

### Common Issues

1. **Permission Errors**
   - Ensure storage and bootstrap/cache directories are writable
   - Run: `chmod -R 755 storage bootstrap/cache`

2. **Migration Errors**
   - Check database connection settings in `.env`
   - Ensure database exists and is accessible

3. **Livewire Issues**
   - Clear cache: `php artisan cache:clear`
   - Check browser console for JavaScript errors

4. **Asset Compilation Issues**
   - Reinstall Node dependencies: `npm install`
   - Rebuild assets: `npm run build`

### Getting Help
For additional support:
1. Check Laravel documentation: https://laravel.com/docs
2. Check Livewire documentation: https://laravel-livewire.com/docs
3. Review application logs in `storage/logs/laravel.log`

## Recent Updates and Changes

### September 23, 2025

#### Level Management System Enhancement
- **New Feature**: Added dedicated Level Management system with centralized level administration
- **Database Structure**: Created new `levels` table to store level definitions with the following schema:
  - `id` (bigint, primary) - Unique identifier
  - `name` (string, unique) - Internal level identifier (e.g., "starter", "elementary")
  - `display_name` (string) - User-friendly name shown to students
  - `description` (text, nullable) - Detailed description of the level
  - `order` (integer, unique) - Sequence for level progression
  - `is_active` (boolean, default: true) - Status flag for level availability
  - `created_at`, `updated_at` (timestamps) - Audit fields
- **Admin Interface**: Added comprehensive CRUD interface for level management:
  - Create new levels with custom names, display names, descriptions, and order
  - Edit existing level properties
  - Delete levels (with validation to prevent deletion of levels in use)
  - View detailed level information including usage statistics
- **Navigation Integration**: Added "Levels" menu item to admin navigation for easy access
- **Placement Test Integration**: Updated placement test forms to use dynamic level dropdowns:
  - Level selection dropdowns now populate from the `levels` database table
  - Ensures consistency across all placement tests
  - Prevents typos and invalid level names
  - Supports custom levels added by administrators
- **Validation Improvements**: Enhanced level validation throughout the application:
  - Placement test level mapping now validates against existing levels in database
  - Prevents assignment of non-existent levels
  - Provides better error handling for level-related operations
- **Seeder Implementation**: Created `LevelSeeder` to populate the database with default Pearson GSE-aligned levels:
  - Starter (Order: 1) - GSE 22-35 (CEFR A1-A1+)
  - Elementary (Order: 2) - GSE 30-42 (CEFR A1+-A2)
  - Pre-Intermediate (Order: 3) - GSE 36-46 (CEFR A2-B1-)
  - Intermediate (Order: 4) - GSE 46-58 (CEFR B1)
  - Upper Intermediate (Order: 5) - GSE 57-67 (CEFR B2)
  - Advanced (Order: 6) - GSE 66-78 (CEFR C1)
- **Flexibility Enhancement**: Administrators can now:
  - Add custom levels beyond the standard Pearson GSE levels
  - Modify level properties to match organizational needs
  - Deactivate levels without deleting them
  - Reorder levels to change progression sequence
- **Backward Compatibility**: Maintained compatibility with existing placement tests, courses, and user levels
- **Future-Proofing**: Created foundation for advanced level features:
  - Level prerequisites
  - Custom level metadata
  - Enhanced reporting capabilities

This enhancement provides administrators with complete control over the leveling system while maintaining consistency and data integrity across the platform. The new Level Management system enables organizations to customize their level structure while preserving the benefits of the Pearson GSE alignment.

### September 22, 2025

#### Navbar Enhancement - Persistent Placement Test Access
- Added permanent "Placement Test" menu item to the main navigation bar for student users
- Implemented responsive design with consistent placement test access on both desktop and mobile views
- Added proper active state highlighting when users are on placement test pages
- Integrated standardized iconography consistent with other navigation items
- Ensured persistent visibility of placement test access regardless of completion status
- Enhanced user experience by allowing students to easily access, review, and retake placement tests

This update addresses the requirement to keep the placement test accessible in the navigation menu even after students have completed the test, providing ongoing access for review and retakes.

### September 21, 2025

#### Audio and Speaking Practice Interface Enhancement
- Implemented relational database structure for audio and speaking practice features with separate `lesson_audio` and `lesson_speaking` tables
- Added dedicated models (`LessonAudio` and `LessonSpeaking`) with proper relationships to `Lesson` model
- Created migration files to establish new table structure and remove deprecated fields from lessons table
- Updated `LessonController` to handle audio and speaking practice as separate entities with dedicated methods (`handleAudioUpdate` and `handleSpeakingUpdate`)
- Implemented tab-based interface in admin lesson edit form for better organization of audio and speaking practice settings
- Added rich text editors (CKEditor) for audio and speaking practice instructions with full formatting capabilities
- Reorganized student lesson view to display audio listening practice before speaking practice in the content flow
- Enhanced form layout in admin panel with instructions on the left side and settings (file upload/duration) on the right side for improved UX
- Added enable/disable checkboxes for both audio and speaking practice features to control visibility in student view
- Implemented proper file handling and cleanup for audio files with automatic deletion of old files when replaced
- Added comprehensive validation for audio files (MP3/WAV, 5MB max) and speaking duration (1-300 seconds)

#### Database Structure Improvements
- Created dedicated tables for audio and speaking practice with normalized structure:
  - `lesson_audio`: Stores audio file paths, descriptions, and enable status
  - `lesson_speaking`: Stores speaking duration, descriptions, and enable status
- Removed deprecated fields from `lessons` table for cleaner database schema
- Added proper foreign key constraints and indexing for optimal performance
- Implemented explicit table naming in models to prevent Laravel pluralization issues

#### User Interface Enhancements
- Admin Panel:
  - Tab-based navigation for lesson editing (Basic Info, Audio Listening, Speaking Practice, Quiz)
  - Improved form organization with clear section headings and visual separation
  - Responsive grid layout for better presentation on different screen sizes
  - Enhanced checkbox controls with clear labeling for enabling/disabling features
- Student View:
  - Reordered content display sequence (Video → Content → Audio Listening → Speaking Practice)
  - Conditional rendering of audio and speaking practice sections based on enable status
  - Rich text formatting for instructions with consistent styling

These updates provide a more modular and maintainable architecture for audio and speaking practice features while improving the user experience for both instructors and students. The relational structure allows for better scalability and data integrity, while the enhanced UI makes it easier to manage and access these features.

### September 19, 2025

#### Speaking Practice Recording Enhancement
- Implemented comprehensive speaking practice recording functionality for lessons
- Added audio recording capability using MediaRecorder API with WebM format support
- Integrated SweetAlert2 for improved user experience with recording confirmations
- Implemented automatic recording save to server storage without page refresh
- Added recording playback functionality with native HTML5 audio player
- Implemented recording overwrite protection with user confirmation dialogs
- Added recording deletion capability with proper file cleanup from storage
- Enhanced UI/UX with real-time recording status indicators and timers
- Implemented responsive design for recording controls across all device sizes
- Added comprehensive error handling for microphone access and recording failures
- Integrated detailed logging for debugging and monitoring recording activities

#### Audio Storage and Management
- Added `student_recordings` database table for tracking student audio recordings
- Implemented file storage in `storage/app/public/student-recordings` with public access
- Added automatic cleanup of old recordings when new ones overwrite existing files
- Implemented proper file path management and database synchronization
- Added file size validation (5MB max) and format restrictions (wav, mp3, webm)

#### Real-time UI Updates
- Eliminated page refresh requirements for recording operations
- Implemented Livewire-powered real-time UI updates for recording status
- Added seamless transition between recording states (idle, recording, saved)
- Enhanced user feedback with success/error notifications using SweetAlert2
- Improved recording preview functionality with instant playback capability

#### User Experience Improvements
- Added intuitive recording controls with clear visual indicators
- Implemented recording timer with automatic stop functionality
- Enhanced recording confirmation workflow with overwrite protection
- Added recording quality preview before permanent save
- Improved accessibility with proper focus states and keyboard navigation
- Enhanced mobile responsiveness for recording interface

These updates significantly enhance the educational platform by providing students with powerful speaking practice capabilities. The implementation leverages modern web technologies to deliver a smooth, intuitive recording experience without requiring page refreshes. The addition of overwrite protection and comprehensive error handling ensures data integrity while maintaining an excellent user experience.

### September 18, 2025

#### Course Thumbnail Management Enhancement
- Added thumbnail upload functionality to admin course management
- Implemented file upload handling in `Admin\CourseController` for both create and update operations
- Modified course create/edit forms to include thumbnail upload field with validation (max 2MB, jpeg/png/jpg/gif formats)
- Updated database storage to save thumbnail paths in the `thumbnail_path` column
- Added automatic thumbnail deletion when courses are deleted
- Implemented proper file storage in `storage/app/public/thumbnails` with public access via `/storage/thumbnails/{filename}`
- Created symbolic link between `public/storage` and `storage/app/public` for thumbnail access
- Added recommended thumbnail size guidance (400px x 250px) in admin forms and documentation

#### Student Course Thumbnail Display Fix
- Fixed thumbnail display issue in student course listings
- Updated `livewire/course-list.blade.php` to properly reference stored thumbnail files using `asset('storage/' . $course->thumbnail_path)`
- Ensured thumbnails are correctly displayed with proper styling and fallback for courses without thumbnails

#### Course Enrollment Functionality Enhancement
- Implemented functional "Enroll Now" button in course detail view
- Added `enroll()` method to `CourseDetail` Livewire component to redirect users to the first lesson of a course
- Modified course detail view to use Livewire's `wire:click` directive for enrollment action
- Clarified enrollment mechanism: users are considered "enrolled" when they have progress records in any lesson of a course
- Improved dashboard to display "Courses Enrolled" based on this implicit enrollment model

#### Thumbnail Size Optimization
- Refined recommended thumbnail size based on actual display dimensions in course listings
- Updated documentation to recommend 400px x 200px (2:1 ratio) for optimal display across all breakpoints
- Added technical analysis of thumbnail display in `livewire/course-list.blade.php` showing `h-48` (192px) height with responsive width
- Specified that thumbnails are displayed with `object-cover` CSS property which crops images to fit the container
- Recommended size accounts for 3-column grid on desktop (lg:grid-cols-3), 2-column on tablet (md:grid-cols-2), and 1-column on mobile

These updates enhance both the administrative and student experience with courses. Administrators can now easily add visual context to courses through thumbnails, while students benefit from a functional enrollment process that guides them directly into the learning content. The thumbnail display fix ensures a more engaging course browsing experience.

### September 12, 2025

#### Course Page UI/UX Improvements
- Refined student course detail page with cleaner, more focused design
- Integrated course information and progress tracking in a unified header section
- Improved lesson cards with better spacing and visual hierarchy
- Added "Continue from lesson X" indicator to help students resume learning
- Enhanced responsive design for better mobile experience
- Streamlined enrollment actions with more prominent buttons

#### Course Completion Status Enhancement
- Added automatic lesson completion tracking when students finish associated quizzes
- Implemented real-time course completion status calculation without database modifications
- Added "Completed" badges to course cards in the main course catalog (http://127.0.0.1:8000/courses) for authenticated users
- Enhanced dashboard to display course progress with percentage and progress bars
- Removed redundant "Completed" badges from dashboard since progress information is already displayed
- Improved user experience by providing clear visual feedback on course completion status

#### Quiz and Lesson Integration
- Automatically mark lessons as completed when associated quizzes are finished
- Enhanced quiz completion flow to update lesson progress in real-time
- Improved quiz result viewing experience with better navigation and feedback

These updates provide students with better visibility into their progress through courses and create a more seamless learning experience by automatically tracking completion status as they progress through lessons and quizzes.

### September 11, 2025

#### View Switching Enhancement
- Added "View as Student" button in admin navbar for easy switching between admin and student perspectives
- Added "View as Admin" button in student navbar for admin users to switch back to admin view
- Implemented seamless navigation between views without requiring logout/login
- Enhanced responsive design for view switching on mobile devices

#### Navigation Improvements
- Fixed dropdown menu functionality in both admin and student layouts
- Improved mobile navigation menu toggle behavior
- Ensured consistent navigation structure across all device sizes
- Added proper JavaScript event handling for dropdown menus

#### UI/UX Enhancements
- Streamlined navigation order in student view (Dashboard → Courses)
- Streamlined navigation order in admin view (Dashboard → Users → Courses → Reports)
- Improved visual consistency between admin and student layouts
- Enhanced accessibility with proper focus states and keyboard navigation

These updates improve the user experience for administrators by allowing them to easily preview the student perspective without leaving their session. The navigation improvements ensure consistent and intuitive access to all application features across different user roles and device types.

### September 9, 2025

#### Course Management Improvements
- Added `order` field to courses table for custom course ordering
- Implemented course ordering functionality in admin panel
- Updated course index view to display courses in custom order
- Enhanced course creation/edit forms with order input field

#### Breadcrumb Navigation
- Created reusable breadcrumb component for consistent navigation
- Added breadcrumbs to all admin pages:
  - Courses management (index, create, edit, show)
  - Lessons management (create, edit, show)
  - Quizzes management (create, edit, show)
  - Questions management (create, edit, show)
- Added breadcrumbs to student-facing pages:
  - Student dashboard
  - Course listing and detail pages
  - Lesson viewing pages
  - Quiz result pages
- Added breadcrumbs to user profile page

#### Route Fixes
- Fixed incorrect route references in views:
  - Corrected `admin.courses.createLesson` to `admin.courses.lessons.create`
  - Corrected `admin.lessons.show` to `admin.courses.lessons.show`
- Updated all affected views to use proper nested resource route naming
- Cleared view cache to ensure changes take effect

#### UI/UX Improvements
- Enhanced course detail page lessons section with table layout
- Added full CRUD functionality for lessons directly on course detail page:
  - View individual lesson details
  - Edit lesson content
  - Delete lessons with confirmation
- Improved visual consistency with courses index page styling
- Removed unnecessary icons and cleaned up button layouts

#### Bug Fixes
- Resolved "The is trial field must be true or false" validation error
- Fixed checkbox handling for trial course selection
- Corrected route naming issues throughout the application
- Improved form validation and error handling

These updates have significantly improved the admin user experience, making it easier to manage courses, lessons, quizzes, and questions while maintaining consistent navigation and interface design across the application.

## Recent Updates and Changes

### September 24, 2025

#### Bug Fixes and System Improvements
Fixed critical issues identified through comprehensive testing to improve application stability and functionality:

1. **Route Configuration Fixes**:
   - Added missing `placement-tests.submit` route for placement test submissions
   - Corrected route naming inconsistencies in admin placement test routes
   - Fixed route caching issues that prevented proper route resolution

2. **Model Factory Improvements**:
   - Added `HasFactory` trait to `Lesson` and `Progress` models to enable proper factory usage in tests
   - Ensured all models used in testing have proper factory support

3. **Database Integrity Enhancements**:
   - Improved `current_level` attribute handling in User model with proper default value enforcement
   - Added attribute casting to ensure `current_level` never becomes null

4. **Access Control System Refinements**:
   - Enhanced course access restriction logic to properly enforce level-based access controls
   - Improved route middleware configuration for better user role validation

5. **Test Infrastructure Improvements**:
   - Resolved CSRF token mismatches in POST request tests
   - Fixed route resolution issues that caused 404 errors in feature tests
   - Corrected middleware configurations for admin-only routes

These updates have significantly improved the application's reliability and test coverage, resolving multiple critical issues that were preventing proper functionality of the placement test and level progression systems. The application now passes a greater number of tests and provides a more stable user experience.

### September 23, 2025

#### User Management System Enhancement
- Fixed critical issue with admin user update functionality that prevented form submission
- Resolved JavaScript error in user edit form caused by improper selector syntax in `document.querySelector`
- Improved form selection by adding explicit form ID (`user-update-form`) and using `document.getElementById` for reliable element access
- Enhanced UserController validation logic with conditional level field validation (only applied when user role is 'student')
- Added comprehensive error handling and detailed logging to track user update operations
- Implemented proper null handling for empty level fields, converting empty strings to null values for database compatibility
- Added detailed console logging in JavaScript to track form submission data and identify potential issues

#### Route Configuration Cleanup
- Removed duplicate "User routes" comment in web.php that was causing IDE confusion
- Verified all route definitions are properly structured and free of syntax errors
- Confirmed PHP syntax validity with `php -l` command

#### Technical Improvements
- Enhanced form reliability by fixing JavaScript selector issues that were preventing form submission
- Improved validation handling to only apply level-related rules when appropriate
- Added robust error handling with detailed logging for easier debugging
- Streamlined user update process with better data handling for level-related fields

These updates resolve the critical issue where administrators were unable to update user information in the admin panel. The JavaScript error that was preventing form submission has been fixed, and the backend validation logic has been improved for better reliability and error handling.

## TOEFL Diagnostic and Assessment System

### Overview
The TOEFL Diagnostic and Assessment System is a comprehensive feature designed specifically for TOEFL preparation and skill assessment. The system provides students with accurate TOEFL-level evaluations across all four sections (Reading, Listening, Speaking, Writing) and offers personalized learning paths based on individual performance patterns.

### TOEFL Score Band Alignment
The system implements the official TOEFL scoring scale (0-120) with performance level classifications:

| Total Score | Performance Level | CEFR Equivalent | Description |
|-------------|-------------------|------------------|-------------|
| 110-120 | Expert | C2 | Can use English fluently and spontaneously |
| 95-109 | Very Good | C1 | Can use English effectively for professional purposes |
| 80-94 | Good | B2 | Can use English effectively and independently |
| 65-79 | Fair | B1 | Can use English in familiar situations |
| 50-64 | Limited | A2 | Can communicate in basic English |
| 0-49 | Very Limited | A1 | Can understand and use familiar phrases |

### Key Components

#### 1. TOEFL Diagnostic Test
- **Purpose**: Comprehensive assessment of TOEFL readiness across all four sections
- **Structure**: Balanced questions covering all TOEFL question types for each section
- **Scoring**: Automatic scoring with detailed performance analysis and recommendations
- **Time Management**: 120-minute duration mimicking real TOEFL test conditions
- **Performance Analysis**: Detailed breakdown of strengths and weaknesses by section

#### 2. Section-Specific Practice Courses
- **Reading Section**: Factual information, inference, vocabulary, prose summary, sentence insertion questions
- **Listening Section**: Gist questions, detail questions, speaker attitude, organization questions
- **Speaking Section**: Independent tasks and integrated speaking with recording and evaluation
- **Writing Section**: Integrated writing tasks and independent essays with comprehensive rubrics

#### 3. Performance Tracking System
- **Score History**: Track improvement over time with detailed section-wise scores
- **Weak Area Detection**: Automatic identification of sections needing improvement
- **Progress Analytics**: Comprehensive analytics on practice session performance
- **Target Score Monitoring**: Set and track progress toward specific TOEFL score goals

#### 4. Personalized Learning Paths
- **Course Recommendations**: Based on diagnostic results and performance patterns
- **Difficulty Leveling**: Courses organized by skill level (Beginner, Intermediate, Advanced)
- **Target Score Ranges**: Each course designed for specific score improvement ranges
- **Adaptive Content**: Content adapts to student performance and progress

### TOEFL Question Type Support

#### Reading Section Question Types
- **Factual Information**: Direct understanding of explicit information
- **Negative Factual Information**: Identifying what is NOT mentioned in the passage
- **Inference Questions**: Understanding implied meanings and conclusions
- **Rhetorical Purpose**: Understanding why the author includes specific information
- **Vocabulary**: Understanding word meanings in context
- **Reference**: Understanding what pronouns and other references refer to
- **Sentence Insertion**: Identifying where sentences best fit in a passage
- **Prose Summary**: Selecting main ideas that best summarize the passage
- **Fill in Table**: Organizing information from the passage into categories

#### Listening Section Question Types
- **Gist Content**: Understanding the main topic or purpose of conversations/lectures
- **Gist Purpose**: Understanding the primary reason something is said
- **Detail Questions**: Remembering specific information mentioned
- **Function Questions**: Understanding the purpose of statements
- **Attitude Questions**: Understanding the speaker's opinion or feelings
- **Organization Questions**: Understanding how information is structured
- **Connecting Content**: Understanding relationships between ideas

#### Speaking Section Question Types
- **Independent Tasks**: Personal preference, choice, and opinion questions
- **Integrated Tasks**: Campus situations, academic courses, reading/listening integration
- **Scoring**: Based on delivery, language use, and topic development

#### Writing Section Question Types
- **Integrated Writing**: Summarizing and comparing reading and listening passages
- **Independent Essay**: Expressing and supporting opinions on given topics
- **Scoring**: Based on organization, development, language use, and mechanics

### Implementation Details

#### Enhanced User Profiles
- **TOEFL Target Score**: Personal score goals for motivation and progress tracking
- **Section Scores**: Individual tracking of Reading, Listening, Speaking, Writing scores
- **Performance History**: Complete record of all practice sessions and diagnostic attempts
- **Study Notes**: Personal notes and study strategies storage
- **Weak Areas**: Automatic identification of sections needing focus

#### Advanced Question Management
- **TOEFL Question Types**: Support for all official TOEFL question formats
- **Scoring Rubrics**: Detailed evaluation criteria for speaking and writing
- **Sample Answers**: High-quality examples for student reference
- **Time Limits**: Section-specific timing to match real test conditions

#### Comprehensive Analytics
- **Section Performance**: Detailed analysis by section with trend identification
- **Score Progression**: Track improvement over time with visual analytics
- **Practice Patterns**: Identify most effective study times and methods
- **Achievement Tracking**: Monitor completion of courses and practice milestones

### User Experience

#### For Students
- **Initial Diagnostic**: Comprehensive TOEFL assessment to establish baseline skills
- **Personalized Dashboard**: Section-wise scores, progress tracking, and recommendations
- **Targeted Practice**: Courses and exercises specifically addressing weak areas
- **Progress Motivation**: Clear indicators of improvement and achievements
- **Flexible Learning**: Access to appropriate content based on current skill level

#### For Administrators
- **Diagnostic Test Management**: Create and manage comprehensive TOEFL assessments
- **Content Curation**: Organize courses by section, difficulty, and target score ranges
- **Performance Monitoring**: Detailed analytics on student progress and effectiveness
- **Intervention Tools**: Identify students who need additional support
- **Success Tracking**: Monitor overall program effectiveness and student outcomes

### Benefits
- **Targeted Improvement**: Focus on specific sections that need the most work
- **Realistic Assessment**: Accurate measurement of TOEFL readiness using official formats
- **Comprehensive Coverage**: Complete preparation for all four TOEFL sections
- **Data-Driven Learning**: Personalized study paths based on actual performance
- **Progress Motivation**: Clear metrics and achievements to maintain engagement
- **Professional Preparation**: Industry-standard TOEFL question types and scoring

This TOEFL Diagnostic and Assessment System transforms the platform into a sophisticated, data-driven TOEFL preparation environment that provides students with targeted, effective preparation while giving administrators comprehensive tools for monitoring and managing student success.
