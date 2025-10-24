# Trial Class Application

A Laravel + Livewire application for managing trial classes with lessons and quizzes.

## Features

- User authentication (register/login)
- Course catalog with trial classes
- Lesson viewer with text content and video
- Quiz system with multiple question types (MCQ, multi-select, essay)
- Progress tracking
- Admin panel for managing courses, lessons, and quizzes

## Requirements

- PHP 8.1+
- Composer
- Node.js & NPM
- MySQL or SQLite database

## Installation

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

- Visit the application in your browser
- Register as a new user or login with:
  - Email: test@example.com
  - Password: password
- Browse courses and lessons
- Take quizzes to test your knowledge

## Documentation

Comprehensive documentation is available in [DOCUMENTATION.md](DOCUMENTATION.md) which includes:
- System architecture
- Database schema
- API endpoints
- Development guidelines
- Deployment instructions

## Development

- Models are located in `app/Models/`
- Livewire components are in `app/Livewire/`
- Views are in `resources/views/`
- Database migrations are in `database/migrations/`
- Seeders are in `database/seeders/`

## License

This project is open-source software licensed under the MIT license.