# Trial Class Application Documentation

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

## Overview

The Trial Class Application is a Laravel + Livewire platform designed for educational institutions to offer trial classes with lessons and quizzes. It allows students to access course materials (text and video content) and take online tests, while providing administrators with tools to manage courses, lessons, and assessments.

## Features

### For Students:
- User authentication (registration/login)
- Course catalog browsing
- Lesson content viewing (text and embedded videos)
- Quiz taking with multiple question types (MCQ, multi-select, essay)
- Progress tracking
- Dashboard with statistics and recommendations
- Placement test for level assessment
- Level-based course access
- Audio listening and speaking practice exercises
- Speaking practice recording with playback

### For Administrators:
- Course management (CRUD operations)
- Lesson management with WYSIWYG editor
- Quiz and question management
- User management
- Progress tracking and reporting
- Content upload capabilities
- Quick switching between admin and student views
- Placement test management with Excel import
- Level assignment and progression tracking
- Bulk course level assignment
- Detailed reporting and analytics

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

## Technology Stack

- **Backend**: Laravel 12.x (PHP 8.2+)
- **Frontend**: Blade templates, Livewire 3.x, TailwindCSS
- **Database**: MySQL/SQLite
- **Authentication**: Laravel Breeze
- **Rich Text Editor**: TinyMCE
- **Asset Compilation**: Vite
- **Testing**: PHPUnit

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
- created_at, updated_at (timestamps)

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
3. Browse courses from the course catalog (filtered by your assigned level)
4. Enroll in courses at your current level
5. Access lessons to view content, videos, and complete audio/speaking practice
6. Take quizzes after completing lessons
7. View results and track progress on the dashboard
8. Complete all courses at your current level to advance to the next level

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
- View courses at their assigned level
- Access lessons and content
- Complete audio listening and speaking practice exercises
- Take quizzes
- View personal progress and results
- Record and playback speaking practice
- Advance through levels by completing courses

### Guest
Limited access:
- View course catalog
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
- `POST /audio/store` - Store audio recordings for speaking practice
- `DELETE /audio/{recording}` - Delete audio recordings

### Student Endpoints
- `POST /audio/store` - Store audio recordings for speaking practice

## Livewire Components

### Frontend Components
- `CourseList` - Displays list of available courses (filtered by user level)
- `CourseDetail` - Shows course details and lessons
- `LessonViewer` - Renders lesson content and tracks progress
- `VideoPlayer` - Wrapper for video embedding
- `QuizRunner` - Handles quiz taking with timer and navigation
- `QuizResult` - Displays quiz results and feedback
- `PlacementTestRunner` - Handles placement test taking with timer and navigation
- `PlacementTestResult` - Displays placement test results and assigned level

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

## Placement Test and Leveling System

### Overview
The Placement Test and Leveling System is a comprehensive feature that allows students to assess their English proficiency and access courses appropriate to their skill level. This system enhances the educational platform by providing personalized learning paths based on individual abilities, utilizing the Pearson Global Scale of English (GSE) for accurate proficiency assessment.

### Pearson Global Scale of English (GSE) Alignment
The system implements the Pearson GSE scale for accurate English proficiency measurement:

| Speakout Level | CEFR | GSE Range |
|----------------|------|-----------|
| Starter | A1–A1+ | 22 – 35 |
| Elementary | A1+–A2 | 30 – 42 |
| Pre-Intermediate | A2–B1- | 36 – 46 |
| Intermediate | B1 | 46 – 58 |
| Upper Intermediate | B2 | 57 – 67 |
| Advanced | C1 | 66 – 78 |

This alignment ensures accurate placement of students based on internationally recognized English proficiency standards.

### Key Components

#### 1. Placement Tests
- **Purpose**: Assess student English proficiency to determine appropriate course levels using GSE-aligned scoring
- **Structure**: Multiple-choice questions with configurable scoring and time limits
- **Management**: Admin interface for creating, editing, and managing placement tests
- **Import**: Excel import functionality for bulk question management
- **Scoring**: Automatic scoring with GSE-aligned level mapping based on score ranges

#### 2. Leveling System
- **Levels**: Six proficiency levels aligned with GSE (Starter, Elementary, Pre-Intermediate, Intermediate, Upper Intermediate, Advanced)
- **Assignment**: Automatic level assignment based on GSE-aligned placement test scores
- **Progression**: Automatic level progression after completing all courses at current level
- **Access Control**: Students can only access courses at their current level or unlocked levels

#### 3. Course Leveling
- **Level Assignment**: Courses are assigned specific GSE-aligned levels during creation
- **Bulk Assignment**: Admin interface for assigning levels to multiple courses
- **Access Filtering**: Course listings automatically filtered based on student's assigned level

#### 4. Student Progression
- **Level Tracking**: Dashboard displays current GSE-aligned level and progress toward next level
- **Completion Monitoring**: System tracks course completion to determine level advancement eligibility
- **Automatic Advancement**: Students automatically progress to next GSE level after completing all courses at current level

### Implementation Details

#### Database Schema Extensions
- Added `level` column to `courses` table to store course level assignments
- Extended `users` table with:
  - `has_taken_placement_test` (boolean) - Tracks if student has completed placement test
  - `assigned_level` (string) - Initial level assigned based on placement test aligned with GSE
  - `current_level` (string) - Student's current accessible level
  - `unlocked_levels` (json) - Array of levels unlocked by student

#### New Database Tables
- `placement_tests` - Stores placement test definitions with GSE-aligned level mapping
- `placement_test_questions` - Contains questions for placement tests
- `placement_test_attempts` - Tracks student attempts at placement tests
- `placement_test_answers` - Stores student answers for placement test questions

#### Core Functionality
- **Level Assignment Service**: Algorithm to determine appropriate GSE-aligned level based on test scores
- **Progression Logic**: System to monitor course completion and automatically advance students to next GSE level
- **Access Control**: Middleware to restrict course access based on student levels
- **Reporting**: Comprehensive dashboards for admins to monitor student progress and placement test results

### User Experience

#### For Students
- **Initial Assessment**: Prompt to take GSE-aligned placement test upon first login
- **Personalized Catalog**: Course catalog filtered to show only accessible courses based on GSE level
- **Progress Tracking**: Dashboard showing current GSE level, progress, and advancement requirements
- **Level Advancement**: Automatic progression notification after completing level requirements aligned with GSE

#### For Administrators
- **Test Management**: Interface to create, edit, and manage GSE-aligned placement tests
- **Question Import**: Excel import functionality for bulk question management
- **Level Assignment**: Tools to assign GSE-aligned levels to courses and students
- **Progress Monitoring**: Dashboards to track student progression and placement test results
- **Reporting**: Detailed analytics on placement test performance and GSE level distribution

### Benefits
- **Personalized Learning**: Students access content appropriate to their GSE-aligned skill level
- **Structured Progression**: Clear pathway from beginner to advanced levels using internationally recognized standards
- **Efficient Resource Allocation**: Courses tailored to specific GSE proficiency levels
- **Enhanced Engagement**: Reduced frustration from overly difficult or simplistic content
- **Measurable Progress**: Clear metrics for student advancement and institutional effectiveness using GSE benchmarks
- **International Standards**: Alignment with Pearson's Global Scale of English for global recognition

This placement test and leveling system transforms the Trial Class Application from a generic course platform into a sophisticated, adaptive learning environment that grows with each student's abilities, utilizing internationally recognized Pearson GSE standards for accurate proficiency assessment.