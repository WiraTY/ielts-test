# Failed PHPUnit Tests Summary

## Overview
35 tests failed out of 101 total tests executed. This document provides a summary of all failing tests and their categories.

## Failed Tests by Category

### Authentication Tests (6 failures)
- `Tests\Feature\Auth\AuthenticationTest`
  - login screen can be rendered
  - navigation menu can be rendered
- `Tests\Feature\Auth\EmailVerificationTest`
  - email verification screen can be rendered
- `Tests\Feature\Auth\PasswordConfirmationTest`
  - confirm password screen can be rendered
- `Tests\Feature\Auth\PasswordResetTest`
  - reset password link screen can be rendered
  - reset password screen can be rendered
- `Tests\Feature\Auth\RegistrationTest`
  - registration screen can be rendered

### Course Access Restriction Tests (6 failures)
- `Tests\Feature\CourseAccessRestrictionTest`
  - student can only see courses at their level
  - student can access course detail at their level
  - student can access course detail at lower unlocked level
  - student cannot enroll in course of higher level
  - new student without placement test can only access starter courses

### Edge Case and Error Tests (7 failures)
- `Tests\Feature\EdgeCaseAndErrorTest`
  - user cannot take placement test if already taken
  - inactive placement test cannot be accessed
  - user cannot submit placement test with invalid answers
  - course without lessons handled properly
  - admin cannot delete placement test with attempts
  - excel import handles malformed files

### End-to-End User Flow Tests (2 failures)
- `Tests\Feature\EndToEndUserFlowTest`
  - complete user journey from registration to level progression
  - admin user journey for managing courses and placement tests

### Example Test (1 failure)
- `Tests\Feature\ExampleTest`
  - the application returns a successful response

### Excel Import Feature Tests (5 failures)
- `Tests\Feature\ExcelImportFeatureTest`
  - admin can download excel template
  - admin can import questions from excel
  - import fails with invalid file type
  - import fails with file too large
  - student cannot access import functionality

### Level Assignment and Progression Tests (2 failures)
- `Tests\Feature\LevelAssignmentProgressionTest`
  - user can access courses at their current level
  - user can access courses at lower unlocked levels

### Placement Test Feature Tests (6 failures)
- `Tests\Feature\PlacementTestFeatureTest`
  - student can start placement test
  - student can submit placement test
  - admin can create placement test
  - admin can edit placement test
  - admin can delete placement test
  - admin can view placement test reports

### Profile Test (1 failure)
- `Tests\Feature\ProfileTest`
  - profile page is displayed

## Critical Issues Identified

1. **Livewire Method Issues**: Multiple tests are failing with "Method Illuminate\Http\Response::assertSeeLivewire does not exist" indicating a potential problem with Livewire installation or configuration.

2. **Route Issues**: Several tests show "Route [admin.admin.placement-tests.import-questions] not defined" suggesting incorrect route naming.

3. **CSRF Token Issues**: Several tests are returning 419 status codes which typically indicate CSRF token problems.

4. **Access Control Issues**: Many course access tests are returning 403 status codes when they should be successful, suggesting issues with the level-based access control system.

5. **Server Error Issues**: Multiple tests are returning 500 status codes indicating internal server errors that need to be investigated.

The majority of the failing tests are related to authentication, course access restrictions, placement tests, and routing issues. These need to be fixed to ensure the application functions properly.