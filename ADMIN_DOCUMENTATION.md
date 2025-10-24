# Admin Documentation for Trial Class Application

## Table of Contents
1. [Overview](#overview)
2. [Admin Dashboard](#admin-dashboard)
3. [User Management](#user-management)
4. [Course Management](#course-management)
5. [Lesson Management](#lesson-management)
6. [Quiz Management](#quiz-management)
7. [Placement Test Management](#placement-test-management)
8. [Level Management](#level-management)
9. [Reporting and Analytics](#reporting-and-analytics)
10. [Content Upload](#content-upload)
11. [View Switching](#view-switching)

## Overview

This documentation provides detailed instructions for administrators on how to use the Trial Class Application. The admin panel offers comprehensive tools for managing courses, lessons, quizzes, users, placement tests, and viewing detailed reports.

## Admin Dashboard

The admin dashboard serves as the central hub for all administrative activities. It provides an overview of key metrics and quick access to management sections.

### Accessing the Dashboard
- Navigate to `/admin/dashboard` after logging in with admin credentials
- Use the main navigation menu to access different sections

### Dashboard Features
- Quick statistics on total users, courses, lessons, and quizzes
- Direct links to user management, course management, and reports
- "Create New Course" button for quick course creation

## User Management

The user management section allows administrators to view, enable, disable, and track all users in the system.

### Accessing User Management
- Click "User Management" in the main navigation menu
- Or click "Manage Users" on the dashboard

### User Management Features
- View all users in the system with their roles
- Enable or disable user accounts
- Track user activity and progress
- Filter users by role or status

### Managing User Accounts
1. To disable a user account:
   - Find the user in the list
   - Click the "Disable" button next to their name
2. To enable a user account:
   - Find the user in the list
   - Click the "Enable" button next to their name

## Course Management

The course management section allows administrators to create, edit, delete, and organize courses.

### Accessing Course Management
- Click "Courses" in the main navigation menu
- Or click "Manage Courses" on the dashboard

### Creating a New Course
1. Click "Create New Course" button
2. Fill in the course details:
   - Title: The name of the course
   - Description: A brief description of the course content
   - Thumbnail: Upload an image for the course (recommended size: 400px x 200px)
   - Trial Course: Check this box to make it a trial course
   - Order: Set the display order for the course
   - Status: Choose "Published" to make it visible to students
   - Level: Assign the appropriate level (starter, beginner, elementary, intermediate, advanced)
3. Click "Create Course"

### Editing a Course
1. From the course list, click "Edit" next to the course
2. Make the necessary changes to the course details
3. Click "Update Course"

### Deleting a Course
1. From the course list, click "Delete" next to the course
2. Confirm the deletion in the popup dialog
3. Note: Courses with lessons cannot be deleted until all lessons are removed

### Bulk Level Assignment
1. Click "Bulk Level Assignment" button on the course list page
2. Select the courses you want to update
3. Choose the level to assign
4. Click "Assign Level to Selected Courses"

## Lesson Management

Lessons are managed within the context of a specific course.

### Accessing Lesson Management
1. Navigate to the course you want to manage
2. Click "Manage" for that course
3. The lessons section will be displayed

### Creating a New Lesson
1. Click "Create New Lesson" button
2. Fill in the lesson details:
   - Title: The name of the lesson
   - Content: Use the WYSIWYG editor to add text content
   - Video URL: Add a video URL if applicable
   - Order: Set the display order for the lesson
   - Duration: Set the estimated duration in seconds
3. Click "Create Lesson"

### Editing a Lesson
1. From the lesson list, click "Edit" next to the lesson
2. Make the necessary changes to the lesson details
3. Click "Update Lesson"

### Deleting a Lesson
1. From the lesson list, click "Delete" next to the lesson
2. Confirm the deletion in the popup dialog

### Audio and Speaking Practice
Lessons can include audio listening and speaking practice exercises:

1. When editing a lesson, navigate to the "Audio Listening" tab
2. Add a description for the audio listening practice
3. Upload an audio file (MP3 or WAV, max 5MB)
4. Enable the audio listening practice with the checkbox
5. Navigate to the "Speaking Practice" tab
6. Add a description for the speaking practice
7. Set the duration for speaking practice (1-300 seconds)
8. Enable the speaking practice with the checkbox

## Quiz Management

Quizzes are managed within the context of a specific lesson.

### Accessing Quiz Management
1. Navigate to the course and lesson you want to manage
2. Click "Manage" for that lesson
3. The quiz section will be displayed

### Creating a New Quiz
1. Click "Create New Quiz" button
2. Fill in the quiz details:
   - Title: The name of the quiz
   - Duration: Set the time limit in minutes
   - Pass Score: Set the minimum score to pass
3. Click "Create Quiz"

### Editing a Quiz
1. From the quiz list, click "Edit" next to the quiz
2. Make the necessary changes to the quiz details
3. Click "Update Quiz"

### Deleting a Quiz
1. From the quiz list, click "Delete" next to the quiz
2. Confirm the deletion in the popup dialog

### Managing Questions
Quizzes contain multiple questions:

1. After creating a quiz, you'll be taken to the question management page
2. Click "Add Question" to create a new question
3. Choose the question type (MCQ, Multi-select, Essay)
4. Add the question text
5. For MCQ and Multi-select questions, add the options and mark the correct answer(s)
6. Set the score for the question
7. Click "Save Question"

### Importing Questions from Excel
1. From the question management page, click "Import Questions"
2. Download the Excel template if needed
3. Prepare your questions in the Excel format
4. Upload the Excel file
5. Choose whether to replace existing questions
6. Click "Import Questions"

## Placement Test Management

The placement test management section allows administrators to create and manage placement tests for level assessment.

### Accessing Placement Test Management
- Click "Placement Tests" in the main navigation menu

### Creating a New Placement Test
1. Click "Create New Test" button
2. Fill in the test details:
   - Title: The name of the placement test
   - Description: A brief description of the test
   - Duration: Set the time limit in minutes (optional)
   - Active: Check this box to make the test available to students
3. Click "Create Placement Test"

### Editing a Placement Test
1. From the test list, click "Edit" next to the test
2. Make the necessary changes to the test details
3. Configure level mapping by adding score ranges and corresponding levels
4. Click "Update Placement Test"

### Deleting a Placement Test
1. From the test list, click "Delete" next to the test
2. Confirm the deletion in the popup dialog
3. Note: Tests with attempts cannot be deleted

### Managing Questions
Placement tests contain multiple questions:

1. After creating a placement test, click "View" to manage questions
2. Click "Add Question" to create a new question
3. Add the question text
4. Add the options and mark the correct answer
5. Set the score for the question
6. Set the order for the question
7. Click "Save Question"

### Importing Questions from Excel
1. From the question management page, click "Import Questions"
2. Download the Excel template if needed
3. Prepare your questions in the Excel format
4. Upload the Excel file
5. Choose whether to replace existing questions
6. Click "Import Questions"

### Level Mapping
Placement tests use level mapping to assign levels based on scores:

1. When editing a placement test, scroll to the "Level Mapping" section
2. Add score ranges and corresponding levels (e.g., "0-20" maps to "starter")
3. The system will automatically assign levels to students based on their scores

## Level Management

The application uses a level-based system to organize courses and track student progress.

### Available Levels
- Starter
- Beginner
- Elementary
- Intermediate
- Advanced

### Level Assignment
1. Courses are assigned levels during creation or editing
2. Students are assigned levels based on placement test results
3. Students can progress through levels by completing all courses at their current level

### Bulk Course Level Assignment
1. From the course management page, click "Bulk Level Assignment"
2. Select the courses you want to update
3. Choose the level to assign
4. Click "Assign Level to Selected Courses"

## Reporting and Analytics

The reporting section provides detailed analytics on student progress, placement test results, and level progression.

### Accessing Reports
- Click "Reports" in the main navigation menu
- Or click "View Reports" on the dashboard

### Available Reports
1. Placement Test Reports:
   - View detailed statistics on placement test attempts
   - See score distributions and level assignments
   - Filter by test, date range, and other criteria

2. User Level Tracking:
   - Track user levels and progress
   - See which users have taken placement tests
   - View unlocked levels for each user

3. Level Progression Tracking:
   - Monitor user progression through different course levels
   - See completion percentages and time to complete levels
   - Filter by level and status

## Content Upload

The application supports various types of content uploads:

### Image Uploads
- Upload images for quiz questions and lesson content
- Supported formats: JPEG, PNG, GIF
- Maximum file size: 2MB

### Audio Uploads
- Upload audio files for listening practice
- Supported formats: MP3, WAV
- Maximum file size: 5MB

### Excel Imports
- Import questions for quizzes and placement tests
- Download templates for proper formatting
- Supported formats: XLSX, XLS, CSV

## View Switching

Administrators can easily switch between admin and student views to preview the student experience.

### Switching to Student View
1. Click "View as Student" in the admin navbar
2. You will be redirected to the student dashboard
3. Navigate the application as a student would

### Switching Back to Admin View
1. Click "View as Admin" in the student navbar
2. You will be redirected to the admin dashboard