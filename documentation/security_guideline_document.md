# Security Guidelines for the "ielts-test" Application

This document outlines security best practices and requirements tailored to the IELTS Test Application (a Laravel + Livewire + Vite web project). It aligns with Security by Design principles and ensures defense in depth across authentication, data protection, input handling, infrastructure, and dependencies.

---

## 1. Authentication & Access Control

### 1.1 User Authentication
- Enforce HTTPS for all login, registration, and token exchange endpoints (TLS 1.2+).
- Use Laravel Breeze’s scaffold but harden it:
  - Require passwords of minimum 12 characters with mixed case, numbers, and symbols.
  - Store passwords with `bcrypt` or `Argon2` + unique per-user salt.
  - Rate-limit login and password-reset routes (e.g., 5 attempts per minute).
  - Implement account lockout after repeated failures.
  - Enforce email verification before granting any session.
- Provide optional MFA (TOTP) for administrators and high-privilege roles.

### 1.2 Session Management
- Use Laravel’s secure session driver (e.g., `database` or `redis`) with `Secure`, `HttpOnly`, `SameSite=Strict` flags.
- Regenerate session IDs on login and logout to prevent session fixation.
- Set short idle timeouts (e.g., 15 minutes) and absolute timeouts (e.g., 8 hours).
- Revoke all active sessions on password change.

### 1.3 Role-Based Access Control (RBAC)
- Define distinct roles: `student`, `instructor`, `admin` in `config/roles.php` or via a database table.
- Use Laravel Policies and Gates for every resource (Course, Quiz, User, Report).
- Perform authorization checks in controllers and Livewire `mount()`/`update()` hooks.
- Fail with HTTP 403 if unauthorized—never reveal detailed reasons.

---

## 2. Input Handling & Processing

### 2.1 Server-Side Validation
- For all web and API endpoints, validate inputs with Laravel Form Requests:
  - Specify rules (`string`, `integer`, `max:255`, `url`, `mimetypes:audio/*`) and custom messages.
  - Sanitize data (strip tags, trim whitespace) before further processing.

### 2.2 Preventing Injection Attacks
- Always use Eloquent or parameterized queries—never interpolate user input into raw SQL.
- Escape outputs in Blade (`{{ }}`) or use Livewire’s automatic escaping.
- For any raw HTML (rich text via TinyMCE), sanitize with a library (e.g., `htmlpurifier`) and apply a strict whitelist.

### 2.3 Mitigating XSS & CSRF
- Enable Laravel’s built-in CSRF protection (`VerifyCsrfToken` middleware) on all state-changing routes.
- Add `Content-Security-Policy` header:
  ```text
  Content-Security-Policy: default-src 'self'; script-src 'self'; style-src 'self' 'unsafe-inline'; img-src 'self' data:;
  ```
- Set `X-Content-Type-Options: nosniff`, `X-Frame-Options: DENY`, and `Referrer-Policy: no-referrer`.

### 2.4 File Upload Security
- Restrict file types (e.g., MP3, WAV for audio) and limit size (e.g., 5 MB).
- Store uploads outside the webroot or use a signed temporary URL via Laravel Storage.
- Generate random filenames and enforce path normalization to prevent traversal.
- Scan uploaded files with an antivirus solution before processing.

### 2.5 Validation of Redirects
- Maintain an allow-list of internal redirect URLs. Reject any redirect parameter not on the list.

---

## 3. Data Protection & Privacy

### 3.1 Encryption in Transit & at Rest
- Enforce HTTPS with HSTS (`Strict-Transport-Security: max-age=31536000; includeSubDomains`).
- Encrypt database backups and use encrypted disk volumes for storage.
- Use AES-256 for any field-level encryption of PII (e.g., national IDs).

### 3.2 Secrets Management
- Do not hardcode API keys, DB credentials, or salts in code.
- Store all secrets in environment variables and inject via CI/CD or use a secrets manager (AWS Secrets Manager, Vault).

### 3.3 Logging & Information Leakage
- Sanitize logs to avoid PII or technical details (stack traces) in production logs.
- Use Monolog with appropriate handlers/formatters—rotate logs and set retention policies.

---

## 4. API & Service Security

### 4.1 Secure API Endpoints
- Serve all API routes (`routes/api.php`) over HTTPS and require token authentication (Laravel Passport or Sanctum).
- Enforce proper HTTP methods: GET for read-only, POST for create, PUT/PATCH for update, DELETE for removal.
- Implement rate limiting per IP and per user on all sensitive endpoints.

### 4.2 CORS & Content Security
- Configure CORS via `CorsServiceProvider`, allowing only trusted origins.
- Reject preflight requests when origin is not allow-listed.

### 4.3 API Versioning
- Namespace your API controllers with `/api/v1/` and bump versions for breaking changes.

---

## 5. Web Application Security Hygiene

- Enable Laravel Debugbar only in local environments. Disable `APP_DEBUG` in production.
- Implement Subresource Integrity (SRI) on any external scripts/styles.
- Use CSP to restrict inline scripts—avoid `unsafe-inline` where possible.
- Regularly audit third-party JS packages loaded via Vite.

---

## 6. Infrastructure & Configuration Management

- Harden server OS (disable SSH root login, update packages daily).
- Run PHP-FPM with a dedicated, low-privileged user.
- Disable unused Apache/Nginx modules and PHP extensions.
- Set file/folder permissions to 750/640, ensure `storage/` and `bootstrap/cache/` are writable only by the web user.
- Use automated patch management for OS, PHP, Composer dependencies.

---

## 7. Dependency Management

- Maintain a `composer.lock` and run `composer audit` or integrate Dependabot/renovate in CI.
- Remove unused packages to reduce the attack surface.
- Pin critical libraries to fixed minor versions and monitor for CVEs.

---

## 8. CI/CD & Testing Security

- Execute `phpunit` tests in CI, enforce 100% policy coverage for authentication and authorization logic.
- Scan code with static analysis (PHPStan, Psalm) and a linter (PHP CS Fixer, Laravel Pint).
- Integrate SCA (Syft + Grype) to detect vulnerable dependencies.
- Secure your CI environment: limit environment variables scope, use read-only tokens for deployment.

---

## 9. Progressive Web App (PWA) Considerations

- Serve `sw.js` and `manifest.json` over HTTPS only.
- Follow the same Content Security Policy for service workers.
- Validate that offline data caching does not expose stale PII.

---

By adhering to these guidelines, the IELTS Test Application will maintain a strong security posture, protect user data, and remain resilient against common web threats. Regularly revisit these practices as the codebase and threat landscape evolve.