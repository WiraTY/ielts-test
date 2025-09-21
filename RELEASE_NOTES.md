# Release Notes

## Version 1.2.0
**Release Date:** September 21, 2025

### Overview
This release introduces a comprehensive placement test and level progression system, enhancing the educational platform with personalized learning paths based on student proficiency levels. Additionally, it includes significant improvements to audio and speaking practice features, providing students with more interactive language learning tools.

### New Features

#### Placement Test and Leveling System
- **Placement Tests**: Added comprehensive placement test functionality allowing students to assess their English proficiency level
- **Level Assignment**: Implemented automatic level assignment based on placement test scores with configurable level mappings
- **Level-Based Course Access**: Students can only access courses at their assigned level or lower unlocked levels
- **Level Progression**: Students automatically progress to the next level after completing all courses at their current level
- **Bulk Level Assignment**: Administrators can assign levels to multiple courses simultaneously
- **Excel Import for Questions**: Placement test questions can be imported from Excel files with template download functionality
- **Detailed Reporting**: Comprehensive reports on placement test results, user levels, and progression tracking

#### Audio and Speaking Practice Enhancements
- **Separate Audio Listening Practice**: Dedicated audio listening practice section with file upload and enable/disable functionality
- **Speaking Practice Recording**: Enhanced speaking practice with browser-based audio recording using MediaRecorder API
- **Recording Management**: Students can record, playback, and delete their speaking practice recordings
- **Real-time UI Updates**: Speaking practice interface with real-time status updates without page refreshes
- **Recording Overwrite Protection**: Confirmation dialogs to prevent accidental recording deletion
- **File Validation**: Audio file validation (MP3/WAV, 5MB max) and speaking duration limits (1-300 seconds)

### Improvements

#### User Experience
- **Tab-based Lesson Editing**: Improved admin lesson editing interface with tab-based navigation for better organization
- **Enhanced Form Layouts**: Better form organization in admin panel with clear section headings and visual separation
- **Responsive Design**: Improved responsive design for all interfaces, including recording controls
- **Rich Text Editors**: Added CKEditor for audio and speaking practice instructions with full formatting capabilities
- **Progressive Content Display**: Reordered student lesson view to display content in a logical flow (Video → Content → Audio Listening → Speaking Practice)

#### Technical Improvements
- **Database Normalization**: Created dedicated tables for audio and speaking practice features for better data integrity
- **Explicit Table Naming**: Implemented explicit table naming in models to prevent Laravel pluralization issues
- **Improved File Handling**: Enhanced file handling and cleanup for audio files with automatic deletion of old files
- **Comprehensive Error Handling**: Added detailed error handling for microphone access and recording failures
- **Detailed Logging**: Integrated comprehensive logging for debugging and monitoring recording activities

### Bug Fixes
- **Thumbnail Display**: Fixed thumbnail display issues in student course listings
- **Route Naming**: Corrected several route naming issues throughout the application
- **Form Validation**: Improved form validation and error handling
- **Checkbox Handling**: Fixed checkbox handling for trial course selection

### API Changes
- Added new endpoints for placement test management and reporting
- Added endpoints for bulk course level assignment
- Added endpoints for audio recording storage and management

### Database Changes
- Added new tables for placement tests, placement test questions, placement test attempts, and placement test answers
- Added new tables for lesson audio and lesson speaking features
- Added new columns to users table for level management (assigned_level, current_level, unlocked_levels, has_taken_placement_test)
- Added new columns to courses table for level assignment
- Added new table for student recordings

### Migration Notes
- Run `php artisan migrate` to apply new database schema changes
- Existing courses will need level assignments through the admin interface
- Existing users will start at the "starter" level until they take a placement test

### Upgrade Instructions
1. Backup your database and application files
2. Pull the latest code from the repository
3. Run `composer install` to install new dependencies
4. Run `npm install` to install new frontend dependencies
5. Run `npm run build` to compile frontend assets
6. Run `php artisan migrate` to apply database changes
7. Update environment variables if needed
8. Clear cache with `php artisan cache:clear`
9. Test the application thoroughly

### Known Issues
- Excel import functionality may have issues with very large files
- Some older browsers may not support MediaRecorder API for speaking practice
- Audio file format support may vary between browsers

### Deprecations
- Deprecated fields in the lessons table have been removed in favor of dedicated audio and speaking tables

### Contributors
- Development Team
- QA Team
- Product Management

### Feedback
Please report any issues or feedback to the development team. We appreciate your input as we continue to improve the platform.