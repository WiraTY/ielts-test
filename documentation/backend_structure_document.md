# Backend Structure Document

## 1. Backend Architecture

**Overview**  
The backend is built on Laravel (PHP) using the MVC pattern and enhanced by Livewire for dynamic interfaces. Key design patterns and frameworks include:

- **Laravel MVC:** Clear separation between Models, Views, and Controllers.
- **Eloquent ORM:** Database abstraction layer for working with SQL databases using expressive syntax.
- **Service Layer:** Business logic is encapsulated in `app/Services/` to keep controllers and Livewire components thin.
- **Repository of Traits:** Code reuse via PHP traits, following Laravel’s own examples (e.g., `HasAttributes`, `HasRelationships`).
- **Event-Driven Components:** Uses Laravel Events and Listeners for decoupled processing (e.g., after quiz submission).

**Scalability, Maintainability, Performance**  
- **Scalability:** Stateless HTTP requests allow horizontal scaling behind a load balancer; queues (using the database driver) offload long-running tasks (email, reporting).
- **Maintainability:** Modular code organization (Controllers, Services, Livewire, Policies) and strong adherence to PSR-4 and SOLID principles.
- **Performance:** Caching (Redis or file cache), optimized Eloquent queries with eager loading, and a PWA setup for offline support.

---

## 2. Database Management

**Database Technology**  
- Type: Relational (SQL)  
- System: MySQL (or PostgreSQL) managed via Laravel’s database layer

**Data Structure and Access**  
- **Migrations:** Version-controlled schema changes (`database/migrations`).
- **Seeders & Factories:** Populate development and test data using FakerPHP providers for localized data.
- **Relationships:** Defined in Eloquent models (one-to-many, many-to-many).
- **Transactions:** Wrapped in DB transactions for critical operations (e.g., placement test completion).
- **Backup & Restore:** Scheduled dumps and point-in-time recovery (PITR) if using AWS RDS or managed service.

**Data Management Practices**  
- Enforce referential integrity with foreign keys.
- Soft deletes for recoverable resources (`deleted_at`).
- Indexing on frequently queried columns (e.g., `user_id`, `course_id`).

---

## 3. Database Schema

**Human-Readable Schema**  
- **Users:** id, name, email, password, role (student/admin), created_at, updated_at
- **Courses:** id, title, description, created_at, updated_at
- **Lessons:** id, course_id, title, content (rich text), order, created_at, updated_at
- **Quizzes:** id, lesson_id, title, time_limit, passing_score, created_at, updated_at
- **Questions:** id, quiz_id, type, prompt, options (JSON), correct_answer, created_at, updated_at
- **Placements:** id, user_id, score, assigned_level, created_at
- **QuizAttempts:** id, user_id, quiz_id, score, started_at, completed_at
- **CourseProgress:** id, user_id, course_id, lesson_id, status (not_started/in_progress/completed), updated_at

**SQL Schema (MySQL)**  
```sql
CREATE TABLE users (
  id BIGINT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(150) NOT NULL,
  email VARCHAR(255) UNIQUE NOT NULL,
  password VARCHAR(255) NOT NULL,
  role ENUM('student','admin') DEFAULT 'student',
  created_at TIMESTAMP NULL,
  updated_at TIMESTAMP NULL
);

CREATE TABLE courses (
  id BIGINT AUTO_INCREMENT PRIMARY KEY,
  title VARCHAR(255) NOT NULL,
  description TEXT,
  created_at TIMESTAMP NULL,
  updated_at TIMESTAMP NULL
);

CREATE TABLE lessons (
  id BIGINT AUTO_INCREMENT PRIMARY KEY,
  course_id BIGINT NOT NULL,
  title VARCHAR(255) NOT NULL,
  content LONGTEXT,
  `order` INT DEFAULT 0,
  created_at TIMESTAMP NULL,
  updated_at TIMESTAMP NULL,
  FOREIGN KEY (course_id) REFERENCES courses(id) ON DELETE CASCADE
);

CREATE TABLE quizzes (
  id BIGINT AUTO_INCREMENT PRIMARY KEY,
  lesson_id BIGINT NOT NULL,
  title VARCHAR(255) NOT NULL,
  time_limit INT DEFAULT 0,
  passing_score INT DEFAULT 0,
  created_at TIMESTAMP NULL,
  updated_at TIMESTAMP NULL,
  FOREIGN KEY (lesson_id) REFERENCES lessons(id) ON DELETE CASCADE
);

CREATE TABLE questions (
  id BIGINT AUTO_INCREMENT PRIMARY KEY,
  quiz_id BIGINT NOT NULL,
  type VARCHAR(50) NOT NULL,
  prompt TEXT NOT NULL,
  options JSON,
  correct_answer TEXT,
  created_at TIMESTAMP NULL,
  updated_at TIMESTAMP NULL,
  FOREIGN KEY (quiz_id) REFERENCES quizzes(id) ON DELETE CASCADE
);

CREATE TABLE placements (
  id BIGINT AUTO_INCREMENT PRIMARY KEY,
  user_id BIGINT NOT NULL,
  score INT,
  assigned_level VARCHAR(50),
  created_at TIMESTAMP NULL,
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

CREATE TABLE quiz_attempts (
  id BIGINT AUTO_INCREMENT PRIMARY KEY,
  user_id BIGINT NOT NULL,
  quiz_id BIGINT NOT NULL,
  score INT,
  started_at DATETIME,
  completed_at DATETIME,
  FOREIGN KEY (user_id) REFERENCES users(id),
  FOREIGN KEY (quiz_id) REFERENCES quizzes(id)
);

CREATE TABLE course_progress (
  id BIGINT AUTO_INCREMENT PRIMARY KEY,
  user_id BIGINT NOT NULL,
  course_id BIGINT NOT NULL,
  lesson_id BIGINT,
  status ENUM('not_started','in_progress','completed') DEFAULT 'not_started',
  updated_at TIMESTAMP NULL,
  FOREIGN KEY (user_id) REFERENCES users(id),
  FOREIGN KEY (course_id) REFERENCES courses(id),
  FOREIGN KEY (lesson_id) REFERENCES lessons(id)
);
```

---

## 4. API Design and Endpoints

**Approach**  
- RESTful API under `/api` namespace  
- Authentication via Laravel Sanctum (token-based) or session cookies

**Key Endpoints**  
- **Authentication**  
  - POST `/api/register` → register new user
  - POST `/api/login` → obtain auth token
  - POST `/api/logout` → invalidate token

- **Users & Profiles**  
  - GET `/api/user` → fetch current user profile
  - PUT `/api/user` → update profile details

- **Courses & Lessons**  
  - GET `/api/courses` → list courses
  - GET `/api/courses/{id}` → course detail (with lessons)
  - POST `/api/courses` → create course (admin)
  - PUT `/api/courses/{id}` → update course (admin)
  - DELETE `/api/courses/{id}` → remove course (admin)
  - GET `/api/lessons/{id}` → get lesson content
  - POST `/api/lessons` → create lesson (admin)

- **Quizzes & Questions**  
  - GET `/api/quizzes/{id}` → quiz detail (with questions)
  - POST `/api/quizzes/{id}/attempt` → submit answers
  - POST `/api/quizzes` → create quiz (admin)
  - POST `/api/questions` → add question (admin)

- **Placement Test**  
  - GET `/api/placement` → start placement test
  - POST `/api/placement` → submit placement answers

- **Reporting**  
  - GET `/api/reports/user/{id}` → user performance
  - GET `/api/reports/course/{id}` → course analytics

All responses follow a consistent JSON structure: `{ status: 'success'|'error', data: {...}, message: '...' }`.

---

## 5. Hosting Solutions

**Environment**  
- Cloud: AWS (recommended), DigitalOcean, or Heroku
- Components:
  - Compute: EC2 (or DigitalOcean Droplets/Heroku dynos)
  - Database: RDS for MySQL/PostgreSQL (managed backups, encryption at rest)
  - File Storage: S3 for audio assets and PWA resources
  - CDN: CloudFront (or DigitalOcean CDN) for static assets

**Benefits**  
- **Reliability:** Managed services with automated failover
- **Scalability:** Auto-scaling groups for EC2 or horizontal dyno scaling
- **Cost-Effectiveness:** Pay-as-you-go model; right-size instances

---

## 6. Infrastructure Components

- **Load Balancer:** AWS ELB to distribute traffic across backend instances
- **Caching Layer:** Redis for:
  - Session storage
  - Query caching (e.g., popular course list)
  - Rate limiting
- **Queue System:** Laravel queues running on worker instances (using database or Redis driver)
- **CDN:** Distribute JS/CSS and audio files via CloudFront
- **SSL/TLS:** Managed via AWS Certificate Manager or Let’s Encrypt
- **DNS:** Route53 or external DNS for domain management

These components collaborate to deliver fast, reliable responses, minimize latency, and handle traffic spikes gracefully.

---

## 7. Security Measures

- **Authentication & Authorization:**
  - Laravel Sanctum (API tokens) or session cookies with CSRF protection
  - Role-based policies (Student vs. Admin) enforced via Laravel Policies
- **Data Encryption:**
  - TLS in transit for all HTTP traffic
  - Encryption at rest on RDS and S3
- **Input Validation & Sanitization:**
  - Request validation rules in Controllers
  - HTML sanitization for rich text (TinyMCE) to prevent XSS
- **Vulnerability Protections:**
  - SQL injection mitigated by Eloquent parameter binding
  - Rate limiting on sensitive routes (login, placement test)
  - Secure headers (CSP, HSTS) via middleware
- **Logging & Auditing:**
  - Monolog writes to rotating files or CloudWatch
  - Audit trails for admin actions (course creation, user role changes)

---

## 8. Monitoring and Maintenance

- **Performance Monitoring:**
  - New Relic, Datadog, or AWS CloudWatch for metrics (CPU, memory, response times)
  - Laravel Telescope for development insights
- **Error Tracking:**
  - Sentry or Bugsnag to capture exceptions and stack traces
- **Health Checks:**
  - Automated pings to `/health` endpoint
  - Alerting via SNS or PagerDuty on failures
- **Backups & Updates:**
  - Automated daily DB backups retained for 30 days
  - Rolling deployments with zero downtime via AWS CodeDeploy or GitHub Actions
  - Regular dependency updates and security patching

---

## 9. Conclusion and Overall Backend Summary

This backend is a robust, scalable Laravel application tailored for IELTS test preparation. It combines:

- A clean MVC+Service architecture with Livewire for reactive UI support.
- A relational database schema optimized for courses, lessons, quizzes, user progress, and reporting.
- A RESTful API to connect with the PWA and third-party clients.
- Managed cloud hosting with load balancing, caching, and CDN for high performance.
- Comprehensive security, monitoring, and maintenance strategies.

Together, these components ensure the system is reliable, maintainable, and ready to scale as user demand grows. Its emphasis on modularity, testing, and modern web features (PWA, offline support) sets it apart as a forward-looking educational platform.