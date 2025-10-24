# Deployment Checklist

## Pre-Deployment Checklist

### Code Preparation
- [ ] Ensure all code changes are committed and pushed to the repository
- [ ] Verify that all tests pass locally (`php artisan test`)
- [ ] Check that all new features are working as expected
- [ ] Review and update documentation (README.md, DOCUMENTATION.md, ADMIN_DOCUMENTATION.md, EXCEL_TEMPLATE_DOCUMENTATION.md)
- [ ] Ensure all environment variables are properly configured in `.env.example`
- [ ] Verify that all dependencies are up to date in `composer.json` and `package.json`
- [ ] Confirm that all database migrations are created and tested
- [ ] Check that all seeders are working correctly

### Environment Setup
- [ ] Verify server requirements are met (PHP 8.2+, MySQL/PostgreSQL, Node.js, Composer)
- [ ] Ensure web server (Apache/Nginx) is properly configured
- [ ] Check that SSL certificate is installed and configured (if required)
- [ ] Verify that file permissions are set correctly (storage, bootstrap/cache directories)
- [ ] Confirm that the database server is accessible and properly configured
- [ ] Ensure that the application can write to storage directories

### Testing
- [ ] Perform final testing in staging environment
- [ ] Test all user flows (student registration, placement test, course access, lesson completion, quiz taking)
- [ ] Test all admin functions (course management, lesson management, quiz management, placement test management)
- [ ] Test file uploads (images, audio files, Excel imports)
- [ ] Test level assignment and progression functionality
- [ ] Test access restrictions (students can only access courses at their level)
- [ ] Test reporting and analytics features
- [ ] Verify that all email notifications are working (if applicable)
- [ ] Test backup and restore procedures

## Deployment Steps

### 1. Backup Current Environment
- [ ] Create a full backup of the current database
- [ ] Create a backup of the current application files
- [ ] Document the current version/tag for rollback purposes

### 2. Prepare New Deployment
- [ ] Clone or pull the latest code from the repository to the server
- [ ] Install/update PHP dependencies:
  ```bash
  composer install --no-dev --optimize-autoloader
  ```
- [ ] Install/update Node dependencies:
  ```bash
  npm install
  ```
- [ ] Build frontend assets:
  ```bash
  npm run build
  ```

### 3. Configure Environment
- [ ] Copy `.env.example` to `.env` if it doesn't exist
- [ ] Update `.env` file with production configuration values
- [ ] Generate application key if needed:
  ```bash
  php artisan key:generate
  ```

### 4. Database Updates
- [ ] Run database migrations:
  ```bash
  php artisan migrate --force
  ```
- [ ] Seed the database with initial data (if needed):
  ```bash
  php artisan db:seed --force
  ```

### 5. File Permissions and Storage
- [ ] Set proper file permissions:
  ```bash
  chmod -R 755 storage bootstrap/cache
  ```
- [ ] Create symbolic link for storage:
  ```bash
  php artisan storage:link
  ```
- [ ] Verify that storage directories are writable

### 6. Web Server Configuration
- [ ] Configure web server to point to the `public` directory
- [ ] Restart web server to apply changes
- [ ] Verify that the application is accessible

### 7. Post-Deployment Verification
- [ ] Test basic application functionality
- [ ] Test admin panel access
- [ ] Test student registration and login
- [ ] Test placement test functionality
- [ ] Test course access and lesson viewing
- [ ] Test quiz taking and results viewing
- [ ] Test audio listening and speaking practice features
- [ ] Test reporting and analytics features
- [ ] Verify that all images and files are loading correctly

### 8. Monitoring and Maintenance
- [ ] Set up application monitoring (if applicable)
- [ ] Configure log rotation
- [ ] Set up backup procedures for database and files
- [ ] Document the deployment in deployment log

## Post-Deployment Tasks

### Immediate Tasks
- [ ] Notify stakeholders of successful deployment
- [ ] Update version number in application (if applicable)
- [ ] Create Git tag for the release
- [ ] Update documentation with release notes

### Follow-up Tasks
- [ ] Monitor application performance and error logs
- [ ] Address any immediate issues reported by users
- [ ] Schedule follow-up review of deployment

## Rollback Plan

### If Issues Are Discovered
- [ ] Immediately notify the team of critical issues
- [ ] Determine if rollback is necessary
- [ ] Restore database from backup
- [ ] Restore application files from backup
- [ ] Revert to previous Git tag/commit
- [ ] Test rolled-back version to ensure functionality
- [ ] Notify stakeholders of rollback

### Rollback Steps
1. Restore database from the pre-deployment backup
2. Restore application files from the pre-deployment backup
3. Revert environment configuration if changed
4. Restart web server
5. Verify that the application is working correctly
6. Notify stakeholders of the rollback

## Contact Information

### Development Team
- Lead Developer: [Name, Email, Phone]
- Backend Developer: [Name, Email, Phone]
- Frontend Developer: [Name, Email, Phone]

### System Administration
- System Administrator: [Name, Email, Phone]
- Database Administrator: [Name, Email, Phone]

### Stakeholders
- Project Manager: [Name, Email, Phone]
- Product Owner: [Name, Email, Phone]