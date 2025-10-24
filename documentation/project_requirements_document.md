# Project Requirements Document (PRD)

## 1. Project Overview

The IELTS Test Application is a web-based platform designed to help students prepare for the IELTS exam by offering structured courses, quizzes, placement tests, and performance reporting. Administrators can create and manage lessons, quizzes, and audio content, while students can register, take practice tests, record spoken responses, and track their progress.

This application aims to replicate real IELTS conditions—timed quizzes, randomized questions, and speaking practice—while providing offline-friendly, installable features via Progressive Web App (PWA) technology. Success will be measured by smooth user experiences (under 2-second page loads), accurate placement test results, and clear, actionable performance analytics for both students and administrators.

## 2. In-Scope vs. Out-of-Scope

**In-Scope (Version 1.0)**
- Course & lesson creation, editing, deletion (rich text, images, embedded media).
- Quiz builder supporting multiple-choice, fill-in-the-blank, matching, and essay questions.
- User registration, login, password reset, role-based access (student vs. admin).
- Adaptive placement test with dynamic question selection and level assignment.
- Performance dashboards showing scores, completion rates, time spent.
- Audio playback controls and speaking recording widgets.
- PWA support: offline caching for core screens, install prompt, service worker.
- Database seeding with FakerPHP for realistic test data (users, courses, quizzes).

**Out-of-Scope (Phase 2+ or Later)**
- Live instructor feedback or real-time chat.
- AI-powered grammar or pronunciation analysis.
- Payment gateways or subscription management.
- Mobile native apps (iOS/Android) beyond PWA.
- Third-party integrations (Zoom, Google Classroom).

## 3. User Flow

A new student visits the homepage and signs up with an email and password. Upon login, they land on the student dashboard, which displays their current level, recommended courses, and quick links to resume an active lesson or quiz. From the sidebar, they can navigate to Courses, Quizzes, Speaking Practice, and Performance Reports.

When an administrator logs in, they see an admin dashboard with high-level metrics—total users, average scores, and pending content approvals. The admin uses a top navigation bar to access Course Management, Quiz Builder, User Management, and Analytics. Changes made in these sections update the student-facing pages instantly via Livewire components.

## 4. Core Features

- **Authentication & Authorization**: Secure sign-up, login, password reset; role-based access control (student/admin) with Laravel Policies.
- **Course & Lesson Management**: Create, edit, and organize lessons with TinyMCE rich text editor; support images, embedded audio/video.
- **Quiz & Question Builder**: Define multiple-choice, fill-in-the-blank, matching, and essay questions; set time limits, randomize order, preview in real time.
- **Placement Test**: Adaptive test engine that selects questions based on prior answers; automatically assigns proficiency level.
- **Audio & Speaking Practice**: Upload/manage audio files; inline recording widget for students to submit spoken responses.
- **Performance Reporting**: Dashboards with charts for individual progress, aggregate stats; CSV/JSON export of reports.
- **Progressive Web App**: Service worker for offline caching of key pages; installable manifest; background sync for submitted quizzes.
- **Data Seeding & Testing**: FakerPHP-based factories and seeders for localized fake data (names, addresses, IDs) to populate development and test environments.

## 5. Tech Stack & Tools

- **Backend**: PHP 8.1+, Laravel 10 (MVC, Eloquent ORM, Policies, Queues, Mail).
- **Frontend**: Blade templates + Livewire for reactive UI; Tailwind CSS for styling; Vite + Laravel Vite Plugin for asset bundling; Axios for AJAX.
- **Rich Text Editor**: TinyMCE.
- **PWA**: Service worker (`sw.js`), `manifest.json`.
- **Database**: MySQL or PostgreSQL; Laravel migrations, factories, seeders.
- **Testing**: PHPUnit + Hamcrest; custom XSD for TeamCity integration.
- **Logging & Debugging**: Monolog; Laravel Telescope (optional); PsySH REPL.
- **Developer Tools**: IDEs like PhpStorm or VSCode with Laravel extensions; Git for version control; Docker (optional) for local environment.

## 6. Non-Functional Requirements

- **Performance**: Page load times under 2 seconds on a 3G network; PWA offline responses under 500ms for cached resources.
- **Scalability**: Support up to 10,000 users with horizontal scaling (database replication, queue workers).
- **Security**: CSRF/XSS/SQL injection protection via Laravel defaults; password hashing (bcrypt); HTTPS-only.
- **Usability**: Mobile-first responsiveness; WCAG 2.1 AA accessibility compliance; consistent UI/UX with Tailwind design system.
- **Reliability**: 99.9% uptime; background queue reliability for email notifications and data exports.

## 7. Constraints & Assumptions

- Hosting environment must support PHP 8.1+, Composer, and a relational database.
- Browser support includes latest Chrome, Firefox, Safari, Edge with service worker capability.
- No third-party payment or AI services in version 1.
- Admins have basic technical literacy for content creation.
- FakerPHP providers are assumed sufficient for all required locales.

## 8. Known Issues & Potential Pitfalls

- **PWA Caching Conflicts**: Overzealous service worker caching may serve stale content. Mitigation: implement proper cache versioning and `stale-while-revalidate` strategy.
- **Livewire Performance**: Large components or excessive AJAX calls can slow UI. Mitigation: split into smaller components, use `wire:ignore` where appropriate.
- **Database Seeder Latency**: Generating massive fake data sets can be slow. Mitigation: batch inserts and limit default seed size.
- **Audio Storage/Playback**: Hosting large audio files may strain disk space and bandwidth. Mitigation: enforce upload limits and consider external storage (S3) later.
- **N+1 Query Risks**: Eloquent relationships may trigger extra queries. Mitigation: use eager loading (`with()`) and query debugging.
- **Accessibility Gaps**: Custom components (record widget) need ARIA roles and keyboard navigation. Mitigation: include automated a11y tests and manual audit.

---

This PRD captures all essential details—features, user journeys, tech choices, and constraints—so an AI model can generate subsequent technical documents (architecture diagrams, API specs, frontend guidelines) without ambiguity.