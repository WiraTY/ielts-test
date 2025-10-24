# Frontend Guideline Document

This document provides a clear, step-by-step guide to the frontend setup of the IELTS Test Application. It covers architecture, design principles, styling, components, state management, routing, performance, testing, and more. Anyone—technical or not—should be able to understand how the frontend is put together and why.

## 1. Frontend Architecture

**Core Frameworks and Tools**
- **Laravel (Blade)**: Handles server-side rendering of HTML templates.
- **Livewire**: Bridges PHP and JavaScript for dynamic, reactive components without writing much JS.
- **Vite**: Bundles and hot-reloads CSS/JS assets for faster development.
- **Tailwind CSS**: Utility-first CSS framework for consistent, rapid styling.
- **Axios**: Simple HTTP client for AJAX requests when needed.
- **TinyMCE**: Rich text editor for course and quiz content.
- **PWA Support**: Service worker (`sw.js`) and `manifest.json` for offline use and installability.

**How It Supports Scalability, Maintainability, and Performance**
- **Component-Based** (Livewire): Encapsulates UI and logic, so you can add or update features in isolation.
- **Modular Assets** (Vite): Splits code into small bundles, speeding up page loads and rebuilds during development.
- **Utility-First CSS** (Tailwind): Eliminates custom, one-off CSS files and encourages reuse of standardized classes.
- **Progressive Enhancement**: The app works as a normal website first; dynamic parts load only when supported.

## 2. Design Principles

1. **Usability**
   - Clear, familiar layouts for test-takers and admins.
   - Immediate feedback on actions (e.g., form validation, live quiz updates).

2. **Accessibility**
   - Semantic HTML (buttons, headings, lists).
   - ARIA labels on custom components (modals, rich text editor).
   - Keyboard navigation and focus states.
   - Color contrast meeting WCAG AA standards.

3. **Responsiveness**
   - Mobile-first approach with Tailwind’s breakpoints (`sm`, `md`, `lg`, `xl`).
   - Flexible grid and flex layouts to adapt to various screen sizes.

**Applying These Principles**
- Buttons and links have clear focus outlines.
- Forms use descriptive labels and error messages.
- The navigation bar collapses into a mobile menu on small screens.

## 3. Styling and Theming

**Styling Approach**
- We use **Tailwind CSS** exclusively (no custom SASS or BEM).
- Tailwind classes live in the markup (e.g., `class="bg-primary text-white p-4 rounded"`).
- Custom utility classes or components are defined in `tailwind.config.js`.

**Theming**
- Centralized in `tailwind.config.js` under `theme.extend`, so colors, fonts, and sizes stay consistent.

**Visual Style**
- **Modern Flat Design** with subtle glassmorphism on select cards (transparent backgrounds + soft shadows).
- Clean lines, minimal gradients, and consistent spacing.

**Color Palette**
- Primary: #1E40AF (deep blue) and #3B82F6 (bright blue)  
- Secondary: #F59E0B (warm amber)  
- Backgrounds & Neutrals: #F3F4F6 (off-white), #E5E7EB (light gray), #9CA3AF (mid gray), #374151 (dark gray)  
- Success: #10B981 (emerald), Warning: #F97316 (orange), Error: #EF4444 (red)

**Typography**
- Font Family: **Inter**, with fallback to `system-ui, -apple-system, BlinkMacSystemFont, sans-serif`.
- Headings: 600–700 font weight; Body text: 400 weight; Line-height ~1.5x for readability.

## 4. Component Structure

**Organization**
- **app/Livewire/**: All Livewire PHP components, grouped by feature (e.g., `Admin/`, `Student/`, `Shared/`).
- **resources/views/components/**: Blade snippets for reusable UI pieces (buttons, cards, modals).
- **resources/views/layouts/**: Main layout files (e.g., `app.blade.php`, `auth.blade.php`).

**Naming Conventions**
- Livewire components use PascalCase (e.g., `UserProfile`, `QuizEditor`).
- Blade files use kebab-case (e.g., `user-profile.blade.php`).

**Reusability**
- Atomic UI: smallest pieces (buttons, inputs) combine into molecules (form-group) and organisms (quiz form).
- Props and slots in Blade components allow customization without duplication.

**Benefits**
- Easy to find and update a component in one place.
- Bugs and visual inconsistencies are localized and quickly fixed.

## 5. State Management

**Livewire-Driven State**
- Livewire components keep their own PHP properties in sync with the browser via AJAX.
- No external state library (like Redux) is needed.

**Data Flow**
1. User interacts (clicks, types).
2. Livewire captures events, sends a request to the server.
3. PHP updates component properties and re-renders a JSON diff.
4. The browser applies minimal DOM changes.

**Cross-Component Communication**
- Use Livewire events: `$this->emit('eventName', $data)` and listeners in other components.

## 6. Routing and Navigation

**Laravel Routes**
- Defined in `routes/web.php` (web interface) and `routes/api.php` (AJAX endpoints).
- Named routes (`->name('courses.index')`) for easy URL generation in Blade (`route('courses.index')`).

**Livewire Routing**
- You can mount components directly in routes:  
  Route::get('/dashboard', Dashboard::class)->name('dashboard');

**Navigation Structure**
- **Main Navbar**: Links to Dashboard, Courses, Quizzes, Reports, Profile.
- **Sidebar** (admin view): Nested links for Course Management, Lesson Builder, User & Role Settings.
- **Breadcrumbs** show user location within nested sections.

## 7. Performance Optimization

1. **Asset Bundling & Caching**
   - Vite handles ES module imports, cache busting, and minification.
   - Tailwind’s PurgeCSS removes unused CSS classes in production.

2. **Lazy Loading**
   - Dynamically import heavy libraries (e.g., TinyMCE) only on editor pages.
   - Use `wire:loading` to delay rendering of non-critical Livewire parts.

3. **Image & Media Optimization**
   - Use responsive images (`srcset`) and modern formats (WebP).
   - Preload critical images and defer offscreen ones.

4. **PWA Caching Strategy**
   - Cache static assets (CSS, JS, icons) on install.
   - Network-first strategy for dynamic API calls, falling back to cache when offline.

5. **Database Queues**
   - Background jobs (emails, analytics) do not block page loads.

## 8. Testing and Quality Assurance

**Unit and Feature Testing**
- **PHPUnit**: Test Livewire components, services, and policies.
- **Livewire Test Helpers**: Assert component renders, emits events, and updates state correctly.

**End-to-End Testing**
- **Laravel Dusk** (recommended): Automate browser steps—login, take a quiz, review results—to verify critical flows.

**Continuous Integration**
- Test results formatted for TeamCity CI/CD using PHPUnit XSD configurations.
- Run tests on each pull request to catch regressions early.

**Code Quality Tools**
- **PHP CS Fixer / Laravel Pint** for PHP style.
- **ESLint** (optional) for any custom JS.
- **Tailwind Linter** (optional) for style consistency.

## 9. Conclusion and Overall Frontend Summary

This guide lays out the building blocks of the IELTS Test Application frontend:
- A **Laravel + Livewire** core for seamless PHP-driven interactivity.
- **Vite** and **Tailwind CSS** for fast, modern asset management and styling.
- **Component-first design** promoting reusability and maintainability.
- **Accessibility** and **responsiveness** baked in, ensuring a smooth experience on any device.
- **Performance** boosted by lazy loading, code splitting, and PWA features.
- **Robust testing** through PHPUnit, Livewire helpers, and Dusk for confidence in every release.

Together, these guidelines ensure that developers can quickly onboard, maintain high code quality, and deliver an engaging, reliable experience for students and administrators alike.