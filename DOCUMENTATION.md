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

### For Administrators:
- Course management (CRUD operations)
- Lesson management with WYSIWYG editor
- Quiz and question management
- User management
- Progress tracking and reporting
- Content upload capabilities
- Quick switching between admin and student views

### Technical Features:
- Responsive design with TailwindCSS
- Video embedding support
- WYSIWYG content editor (TinyMCE)
- Progress tracking
- Quiz timer functionality
- Results calculation and display
- View switching between admin and student perspectives

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
- created_at, updated_at (timestamps)

### Courses
- id (bigint, primary)
- slug (string, unique)
- title (string)
- description (text, nullable)
- thumbnail_path (string, nullable)
- is_trial (boolean, default: true)
- created_by (foreign key to users.id)
- published_at (timestamp, nullable)
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
2. Browse courses from the course catalog
3. Enroll in a trial course
4. Access lessons to view content and videos
5. Take quizzes after completing lessons
6. View results and track progress on the dashboard

### For Administrators
1. Login with admin credentials
2. Access the admin panel at `/admin/dashboard`
3. Manage courses, lessons, quizzes, and questions using the CRUD interfaces
4. Monitor student progress and quiz results
5. Upload content and manage users
6. Switch to student view using "View as Student" button to preview student experience

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
- Switch between admin and student views

### Student
Can access learning content:
- View enrolled courses
- Access lessons and content
- Take quizzes
- View personal progress and results

### Guest
Limited access:
- View course catalog
- Register for an account

## API Endpoints

Currently, the application primarily uses Livewire for interactivity rather than a REST API. However, there are some backend endpoints for file uploads:

### Admin Endpoints
- `POST /admin/questions/upload-image` - Upload images for quiz questions
- `POST /admin/lessons/upload-image` - Upload images for lesson content

## Livewire Components

### Frontend Components
- `CourseList` - Displays list of available courses
- `CourseDetail` - Shows course details and lessons
- `LessonViewer` - Renders lesson content and tracks progress
- `VideoPlayer` - Wrapper for video embedding
- `QuizRunner` - Handles quiz taking with timer and navigation
- `QuizResult` - Displays quiz results and feedback

### Admin Components
- `Admin\CourseForm` - CRUD interface for courses
- `Admin\LessonForm` - CRUD interface for lessons
- `Admin\QuizForm` - CRUD interface for quizzes and questions
- `Admin\UserList` - User management interface
- `Admin\ReportList` - Reporting interface

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
│   ├── layouts/            # Base layouts
│   └── components/         # Reusable Blade components
└── js/                     # JavaScript assets
```

### Key Models and Relationships
- `User` - Can be admin or student
- `Course` - Contains multiple lessons, created by a user
- `Lesson` - Belongs to a course, can have one quiz
- `Quiz` - Belongs to a lesson, contains multiple questions
- `Question` - Belongs to a quiz
- `QuizAttempt` - Tracks a user's attempt at a quiz
- `QuizAnswer` - Stores answers for a quiz attempt
- `Progress` - Tracks user progress through lessons

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