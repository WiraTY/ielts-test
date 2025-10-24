# TOEFL Test Management System

A comprehensive Laravel + Livewire platform designed specifically for TOEFL (Test of English as a Foreign Language) preparation and assessment. The system provides targeted practice across all four TOEFL sections (Reading, Listening, Speaking, Writing), includes a sophisticated diagnostic test system, and offers powerful tools for content management and progress tracking.

![TOEFL Logo](https://upload.wikimedia.org/wikipedia/commons/thumb/1/16/ETS_TOEFL_logo.svg/200px-ETS_TOEFL_logo.svg.png)

## 🎯 Key Features

### For Students
- **📊 Comprehensive TOEFL Diagnostic Test**: Initial assessment to identify strengths and weaknesses across all sections
- **📚 Section-Specific Courses**: Targeted practice for Reading, Listening, Speaking, and Writing sections
- **🎯 Personalized Learning Paths**: Course recommendations based on diagnostic results and performance
- **📈 Advanced Progress Tracking**: Monitor improvement with detailed section-wise analytics
- **🎯 Score Goal Setting**: Set and track progress toward specific TOEFL score targets
- **🗣️ Speaking Practice**: Recording capabilities with evaluation and feedback
- **✍️ Writing Practice**: Integrated and independent essay practice with detailed rubrics
- **🔍 Weak Area Detection**: Automatic identification of sections needing improvement
- **📱 Responsive Design**: Study on any device with optimized mobile experience

### For Administrators
- **🛠️ TOEFL Content Management**: Create and manage section-specific courses with full curriculum control
- **📊 Advanced Analytics Dashboard**: Comprehensive reporting on student performance and system effectiveness
- **📝 Diagnostic Test Creation**: Build comprehensive assessments with multiple question types
- **🎯 Personalization Controls**: Configure recommendation algorithms and learning paths
- **📈 Performance Monitoring**: Track student progress and identify intervention opportunities
- **🔒 User Management**: Complete control over student accounts and access permissions

## 🚀 TOEFL Section Coverage

### Reading Section
- **Question Types**: Factual information, inference, vocabulary, prose summary, sentence insertion
- **Skills Development**: Academic reading comprehension, speed reading, vocabulary building
- **Practice Format**: Timed passages with authentic TOEFL question formats

### Listening Section
- **Question Types**: Gist questions, detail questions, speaker attitude, organization
- **Skills Development**: Academic listening, note-taking, understanding complex conversations
- **Practice Format**: Audio passages with realistic academic content

### Speaking Section
- **Question Types**: Independent tasks and integrated speaking (campus/academic situations)
- **Skills Development**: Fluency, pronunciation, academic speaking strategies
- **Practice Format**: Recording capabilities with preparation time and response timers

### Writing Section
- **Question Types**: Integrated writing and independent essays
- **Skills Development**: Academic writing, essay structure, argument development
- **Practice Format**: Timed writing with comprehensive rubric-based evaluation

## 🛠️ Technology Stack

- **Backend**: Laravel 12.x (PHP 8.2+)
- **Frontend**: Blade templates, Livewire 3.x, TailwindCSS
- **Database**: MySQL/SQLite
- **Authentication**: Laravel Breeze
- **Rich Text Editor**: TinyMCE
- **Asset Compilation**: Vite
- **Testing**: PHPUnit
- **UI Enhancements**: SweetAlert2

## 📊 Score System

The system implements the official TOEFL scoring scale (0-120):

| Performance Level | Score Range | CEFR | Description |
|------------------|--------------|--------|-------------|
| Expert | 110-120 | C2 | Fluent English for academic/professional use |
| Very Good | 95-109 | C1 | Effective English use with good control |
| Good | 80-94 | B2 | Independent and effective English use |
| Fair | 65-79 | B1 | Can handle familiar situations |
| Limited | 50-64 | A2 | Basic English communication |
| Very Limited | 0-49 | A1 | Fundamental English phrases |

## 🏗️ Installation

### Prerequisites
- PHP 8.2+
- Composer
- Node.js & NPM
- MySQL or SQLite database

### Quick Setup

1. **Clone the repository**:
   ```bash
   git clone <repository-url>
   cd toefl-test-management
   ```

2. **Install PHP dependencies**:
   ```bash
   composer install
   ```

3. **Install Node dependencies**:
   ```bash
   npm install
   ```

4. **Environment setup**:
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

5. **Database configuration**:
   ```bash
   # Edit .env file with your database credentials
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=toefl_system
   DB_USERNAME=your_username
   DB_PASSWORD=your_password
   ```

6. **Run migrations and seeders**:
   ```bash
   php artisan migrate
   php artisan db:seed
   ```

7. **Build frontend assets**:
   ```bash
   npm run build
   ```

8. **Start development server**:
   ```bash
   php artisan serve
   ```

Visit `http://localhost:8000` in your browser.

## 🔐 Default Credentials

- **Administrator**:
  - Email: `admin@example.com`
  - Password: `password`

- **Student**:
  - Email: `test@example.com`
  - Password: `password`

## 📖 Usage Guide

### For Students

1. **Take Diagnostic Test**:
   - Click on "TOEFL Diagnostic Test" in the navigation
   - Complete all four sections (120 minutes total)
   - Receive detailed performance analysis and recommendations

2. **Access Personalized Courses**:
   - Browse courses recommended based on your diagnostic results
   - Filter by section (Reading, Listening, Speaking, Writing)
   - Choose appropriate difficulty level (Beginner, Intermediate, Advanced)

3. **Track Progress**:
   - Monitor your section-wise scores on the dashboard
   - Set personal target scores and track improvement
   - View detailed analytics and performance trends

4. **Practice Speaking & Writing**:
   - Record speaking responses with integrated timing
   - Submit writing assignments with detailed rubrics
   - Receive feedback and improve based on evaluations

### For Administrators

1. **Manage Courses**:
   - Create section-specific courses with targeted content
   - Set difficulty levels and score ranges
   - Upload materials and organize curriculum

2. **Create Diagnostic Tests**:
   - Build comprehensive assessments covering all question types
   - Configure scoring rubrics and time limits
   - Set up automated recommendation systems

3. **Monitor Student Progress**:
   - View performance analytics and trends
   - Identify students needing additional support
   - Generate detailed reports and insights

## 📁 Project Structure

```
app/
├── Http/
│   ├── Controllers/          # HTTP controllers
│   └── Livewire/             # Livewire components
├── Models/                  # Eloquent models
│   ├── ToeflScore.php       # TOEFL score tracking
│   ├── ToeflDiagnosticTest.php # Diagnostic test management
│   ├── ToeflPracticeSession.php # Practice session tracking
│   └── ...
├── Livewire/                # Livewire component classes
└── Policies/                # Authorization policies

database/
├── migrations/              # Database schema migrations
├── seeders/                # Database seeders
│   ├── ToeflDiagnosticTestSeeder.php
│   ├── ToeflCourseSeeder.php
│   └── ...
└── factories/              # Model factories for testing

resources/
├── views/
│   ├── admin/              # Admin panel views
│   ├── student/            # Student dashboard views
│   ├── toefl/             # TOEFL-specific views
│   └── layouts/            # Base layouts
└── js/                   # JavaScript assets
```

## 🧪 Testing

### Running Tests
```bash
# Run all tests
php artisan test

# Run specific test file
php artisan test --filter ToeflDiagnosticTestTest

# Run with coverage
php artisan test --coverage
```

### Test Structure
- **Unit Tests**: Model and business logic testing
- **Feature Tests**: Integration and user journey testing
- **Browser Tests**: Full UI/UX testing

## 📚 Documentation

- **Comprehensive Guide**: [DOCUMENTATION.md](DOCUMENTATION.md)
- **Database Schema**: Detailed model relationships and migrations
- **API Documentation**: Available endpoints and data structures
- **Development Guidelines**: Coding standards and best practices

## 🚀 Deployment

### Production Deployment

1. **Server Setup**:
   - PHP 8.2+ with required extensions
   - MySQL 5.7+ or PostgreSQL
   - Nginx/Apache web server
   - SSL certificate

2. **Application Setup**:
   ```bash
   composer install --no-dev
   npm ci
   npm run build
   php artisan optimize
   ```

3. **Environment Configuration**:
   - Set production environment variables
   - Configure database connections
   - Set up file permissions

4. **Database Migration**:
   ```bash
   php artisan migrate --force
   php artisan db:seed --force
   ```

## 🔧 Development

### Adding New TOEFL Question Types

1. **Update Migration**: Add new question type to `questions` table enum
2. **Modify Model**: Update `Question.php` with new type handling
3. **Create Components**: Add Livewire components for new question format
4. **Update Frontend**: Add UI elements for new question type

### Customizing Scoring Rubrics

1. **Edit Model**: Modify evaluation methods in relevant models
2. **Update Database**: Add custom rubric fields if needed
3. **Modify Components**: Update Livewire components for new scoring logic

## 🤝 Contributing

1. **Fork** the repository
2. **Create** a feature branch: `git checkout -b feature/new-feature`
3. **Commit** your changes: `git commit -m 'Add new feature'`
4. **Push** to the branch: `git push origin feature/new-feature`
5. **Submit** a pull request

### Coding Standards
- Follow PSR-12 coding standards
- Use Laravel conventions and best practices
- Write tests for all new features
- Update documentation for significant changes

## 📄 License

This project is open-source software licensed under the MIT license.

## 🆘️ Support

- **Documentation**: Check [DOCUMENTATION.md](DOCUMENTATION.md) for detailed guides
- **Issues**: Report bugs and feature requests on GitHub Issues
- **Community**: Join discussions and share experiences

---

**🎓 Start Your TOEFL Journey Today!**

Whether you're aiming for university admission, professional certification, or personal achievement, this system provides the comprehensive tools and structured approach needed to excel on the TOEFL exam.