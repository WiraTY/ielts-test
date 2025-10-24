# Rancangan Aplikasi Trial Kelas — Laravel + Livewire

**Ringkasan singkat**
Aplikasi ini memungkinkan calon siswa mengikuti *trial class* yang berisi materi pembelajaran mandiri (teks + video) dan tes online. Ditujukan untuk institusi kursus bahasa Inggris. Backend: Laravel 10+, Livewire untuk interaktivitas SPA tanpa full SPA framework. Frontend: Blade + TailwindCSS. Penyimpanan video: S3/Wasabi atau streaming provider (Vimeo/Cloudflare Stream). Autentikasi: Laravel Breeze (simple) atau Jetstream (teams jika perlu).

> Dokumen ini disusun agar mudah dibaca dan diimplementasikan oleh AI agent (mis. Qwen) di VSCode: setiap bagian berisi file-target, perintah artisan/npm, struktur folder, dan contoh migration/model/controller/Livewire component yang diperlukan.

---

## Daftar Isi

1. Tujuan & Scope
2. Fungsionalitas Utama
3. Aktor / Peran
4. User stories prioritas
5. Arsitektur & Teknologi
6. Struktur Folder (skeleton)
7. Model Data (ER / tabel & field)
8. Routes & Endpoints (web + API)
9. Livewire Components (daftar & tanggung jawab)
10. Blade components / UI primitives
11. Migrations, Models, Seeder (contoh)
12. Service & Integrations (video, storage, email)
13. Authorization & Roles
14. Workflows (user flow: sign-up -> trial -> test)
15. Testing & QA
16. Deployment & Environment
17. VSCode / AI Agent Instructions (how-to generate project)
18. Acceptance Criteria & Checklist

---

## 1. Tujuan & Scope

* Menyediakan modul pembelajaran mandiri (text + embed/self-hosted video)
* Menyediakan tes online otomatis (multiple choice + essay optional)
* Tracking progress pengguna dan skor tes
* Admin dapat membuat materi, video, soal, dan melihat analytics
* Lightweight, mudah dikembangkan oleh AI agent

**Batasan**: Tidak termasuk pembayaran, gamification lanjutan, atau video conferencing live (bisa ditambahkan nanti).

---

## 2. Fungsionalitas Utama

* Autentikasi pengguna (register/login, password reset)
* Halaman dashboard user (progress, rekomendasi)
* Katalog trial class / course (daftar unit/lesson)
* Halaman lesson: teks + video + resources
* Tes: soal MCQ (single/multiple correct) dan hasil otomatis
* Admin Panel: CRUD course, lesson, video, tests, users
* Tracking: history test, skor, waktu selesa
* Export CSV laporan pengguna/hasil

---

## 3. Aktor / Peran

* Guest (bisa lihat landing & daftar trial)
* Registered User / Student
* Teacher / Content Editor (opsional)
* Admin (manajemen penuh)

---

## 4. User Stories (prioritas)

1. Sebagai guest, saya ingin mendaftar untuk mencoba trial class.
2. Sebagai student, saya ingin mengakses lesson (teks + video).
3. Sebagai student, saya ingin mengikuti tes setelah lesson dan mendapat skor otomatis.
4. Sebagai admin, saya ingin membuat course, lesson, dan soal.
5. Sebagai admin, saya ingin melihat daftar peserta dan hasil tes.
6. Sebagai student, saya ingin melihat progress saya per lesson dan per course.

---

## 5. Arsitektur & Teknologi

* PHP 8.1+ & Laravel 10+
* Livewire v3 (rekomendasi) atau v2
* TailwindCSS for styling
* MySQL / MariaDB
* Queues: Redis + Horizon (opsional) for video processing/email
* Storage: S3-compatible (or local for dev)
* Video hosting: Cloudflare Stream or Vimeo or direct S3 (gunakan streaming provider untuk performa)
* Testing: Pest atau PHPUnit
* Auth scaffold: Laravel Breeze (simple) + Fortify
* Optional: Laravel Permission (spatie/laravel-permission)

---

## 6. Struktur Folder (recommended skeleton)

```
app/
  Models/
  Http/Controllers/
  Http/Livewire/
  Services/
  Policies/
database/
  migrations/
  seeders/
resources/
  views/
    layouts/
    components/
    admin/
    student/
  js/
routes/
  web.php
  api.php
public/

tests/

.env.example
README.md
```

---

## 7. Model Data (ER) — Tabel & Fields

### users

* id, name, email, password, role, avatar, created\_at, updated\_at

### courses

* id, slug, title, description, thumbnail\_path, is\_trial (bool), created\_by, published\_at, created\_at, updated\_at

### lessons

* id, course\_id, slug, title, content (markdown/html), video\_url (nullable), order (int), duration (int seconds), created\_at, updated\_at

### quizzes

* id, lesson\_id (nullable), title, duration\_minutes, pass\_score (int), created\_at, updated\_at

### questions

* id, quiz\_id, type (mcq/single/multi/essay), question\_text, options (json), answer\_key (json), score

### quiz\_attempts

* id, quiz\_id, user\_id, started\_at, finished\_at, score, status (completed/timeout), metadata(json)

### quiz\_answers

* id, attempt\_id, question\_id, answer (json/text), is\_correct (bool), score\_awarded

### progress

* id, user\_id, lesson\_id, status (not\_started/in\_progress/completed), completed\_at

### videos (opsional)

* id, lesson\_id, provider (vimeo/s3/stream), provider\_id, url, thumbnail, duration

---

## 8. Routes & Endpoints

**Web (Blade + Livewire)**

* GET / -> Landing
* GET /courses -> list
* GET /courses/{slug} -> course detail
* GET /lessons/{course}/{lesson} -> lesson page (Livewire)
* GET /quizzes/{id}/start -> start quiz (Livewire)
* POST /api/webhook/video -> video provider webhook (optional)

**API (for mobile or external)**

* GET /api/courses
* GET /api/courses/{id}/lessons
* POST /api/quizzes/{id}/submit

---

## 9. Livewire Components (daftar & tanggung jawab)

* `CourseList` — menampilkan daftar course
* `CourseDetail` — detail course, daftar lesson
* `LessonViewer` — render teks, embed video, track progress, tombol start quiz
* `VideoPlayer` — wrapper untuk embed provider or HTML5 (passthrough)
* `QuizRunner` — menjalankan tes: menampilkan pertanyaan, timer, navigasi, submit
* `QuizResult` — menampilkan hasil dan feedback
* `Admin.CourseForm` — CRUD course
* `Admin.LessonForm` — CRUD lesson + upload video
* `Admin.QuizForm` — CRUD quiz & questions
* `Admin.UserList` — manajemen pengguna

**Catatan implementasi Livewire:**

* State-heavy interactions (quiz, video progress) di Livewire untuk syncronous UX.
* Use `wire:poll` or `persisted state` carefully for exam timer.

---

## 10. Blade components / UI primitives

* `resources/views/components/card.blade.php`
* `components/video-embed.blade.php` (props: provider, url)
* `components/progress-bar.blade.php`
* `components/question-mcq.blade.php`

---

## 11. Migrations, Models, Seeder (contoh singkat)

**Contoh migration: create\_courses\_table**

```php
Schema::create('courses', function (Blueprint $table) {
  $table->id();
  $table->string('slug')->unique();
  $table->string('title');
  $table->text('description')->nullable();
  $table->string('thumbnail_path')->nullable();
  $table->boolean('is_trial')->default(true);
  $table->foreignId('created_by')->constrained('users');
  $table->timestamps();
});
```

**Contoh model: Lesson**

```php
class Lesson extends Model {
  protected $fillable = ['course_id','slug','title','content','video_url','order','duration'];
  public function course(){ return $this->belongsTo(Course::class); }
}
```

**Seeder quickstart**: buat 2 courses, 3 lessons each, 1 quiz per lesson.

---

## 12. Services & Integrations

* **Video hosting**: gunakan Cloudflare Stream atau Vimeo untuk streaming dan bandwidth. Simpan provider\_id di `videos`.
* **Storage**: S3 (env: AWS\_\*) atau local for dev.
* **Transcoding**: jika self-host video, gunakan external job/service; prefer provider.
* **Email**: Mailgun/SMTP untuk verifikasi & notifikasi
* **Queue**: Redis + Horizon (untuk processing background)

---

## 13. Authorization & Roles

* Gunakan spatie/laravel-permission untuk granular roles (admin, editor, student)
* Policies untuk models: CoursePolicy, LessonPolicy, QuizPolicy

---

## 14. Workflows

**A. Sign-up -> Access trial**

1. User register -> verify email
2. User chooses trial course -> enroll (create enrollment/progress rows)
3. User opens lesson -> mark in\_progress
4. After finishing lesson, user starts quiz -> QuizRunner Livewire handles timer & submissions
5. System calculates score -> save in quiz\_attempts -> show QuizResult

**B. Admin create content**

1. Admin create course -> add lessons -> upload video or set provider link -> create quiz & questions -> publish

---

## 15. Testing & QA

* Unit tests: models, policies
* Feature tests: auth, course access, lesson view, quiz submit
* E2E tests: Laravel Dusk (opsional)
* Use factories and seeders to create testing data

---

## 16. Deployment & Environment

* `.env` variables: DB\_*, AWS\_* or CDN\_\*, QUEUE\_CONNECTION=redis
* Docker recommended: PHP-FPM, Nginx, Redis, MySQL, Node
* CI: GitHub Actions — run tests, run php-cs-fixer, build assets, deploy to server

---

## 17. VSCode / AI Agent Instructions (perintah dan files to generate)

### Perintah inisialisasi (terminal)

```bash
composer create-project laravel/laravel trial-app
cd trial-app
composer require livewire/livewire
npm install
npm install tailwindcss@latest postcss autoprefixer
npx tailwindcss init -p
php artisan migrate
php artisan make:model Course -m
php artisan make:model Lesson -m
php artisan make:model Quiz -m
php artisan make:model Question -m
php artisan make:model QuizAttempt -m
php artisan make:controller Admin/CourseController --resource
php artisan make:livewire CourseList
php artisan make:livewire LessonViewer
```

### Files to auto-generate by AI agent (priority order)

1. `database/migrations/*` (courses, lessons, quizzes, questions, attempts, progress)
2. `app/Models/*` (Course, Lesson, Quiz, Question, QuizAttempt, Progress)
3. `app/Http/Livewire/*` (CourseList.php, LessonViewer.php, QuizRunner.php)
4. `resources/views/layouts/app.blade.php` (with Livewire & Tailwind)
5. `resources/views/livewire/*` (components blades)
6. `routes/web.php` (web routes)
7. `database/seeders/TrialSeeder.php`
8. `README.md` with setup steps and env example

### Tips for AI agent

* Generate migrations with foreign keys and indices.
* Use JSON columns for options & answer\_key for questions for flexible schema.
* For Livewire components, include lifecycle hooks `mount()`, `hydrate()`, and use `protected $listeners` for events (e.g., video-progress).
* Keep UI states minimal: only track current question index, answers array, timer.

---

## 18. Acceptance Criteria & Checklist

* [ ] Register/login flows working
* [ ] Admin can CRUD courses, lessons, quizzes
* [ ] Student can view lesson (text + video)
* [ ] Student can run quiz and get score
* [ ] Results saved and visible in Admin
* [ ] Basic tests exist (unit + feature)

---

## Lampiran: Contoh struktur Livewire `QuizRunner` (pseudocode)

```php
class QuizRunner extends Component {
  public $quiz; // Quiz model
  public $questions; // collection
  public $answers = [];
  public $current = 0;
  public $timeLeft; // seconds

  public function mount(Quiz $quiz){
    $this->quiz = $quiz;
    $this->questions = $quiz->questions;
    $this->timeLeft = $quiz->duration_minutes * 60;
  }

  public function submit(){
    // calculate score
    // store QuizAttempt
  }

  public function render(){
    return view('livewire.quiz-runner');
  }
}
```

---

### Penutup

Dokumen ini disiapkan agar AI agent Qwen dapat langsung menggunakannya untuk membuat skeleton project di VSCode. Jika Anda ingin, saya bisa:

1. Generate *file skeleton* (migrations, models, Livewire components) langsung sekarang, atau
2. Buat *Postman collection* / *OpenAPI* untuk API endpoints, atau
3. Buat *seed data* sample untuk trial course.


19. 🎛️ Hak Akses & Fitur Admin
1. Manajemen User

Melihat daftar semua user (student/teacher).

Mengubah data user (nama, email, role).

Menonaktifkan / menghapus user.

Reset password user (opsional).

2. Manajemen Course

Membuat course baru (judul, deskripsi, thumbnail, status trial/premium).

Mengedit course (update detail).

Menghapus course.

Publish / unpublish course (tampilkan/sembunyikan dari student).

3. Manajemen Lesson

Menambahkan lesson ke dalam course.

Mengatur urutan lesson (drag/drop atau set order).

Menyimpan konten lesson (teks, gambar, resource).

Mengupload atau menautkan video pembelajaran.

Menandai lesson sebagai aktif/tidak aktif.

4. Manajemen Quiz & Soal

Membuat quiz untuk course atau lesson tertentu.

Menambahkan soal (MCQ, multiple answers, essay).

Menentukan jawaban benar & bobot nilai.

Mengedit & menghapus soal.

Mengatur durasi & passing score quiz.

5. Monitoring & Laporan

Melihat progress tiap student (berapa lesson selesai, skor quiz).

Melihat hasil quiz (score per student).

Mengekspor data hasil quiz & progress ke CSV / Excel.

Statistik global: jumlah user aktif, jumlah quiz selesai, rata-rata nilai.

6. Pengelolaan Konten Tambahan (opsional)

Upload materi tambahan (PDF, modul).

Atur banner/promosi di landing page.

Kirim notifikasi/email ke student.

🛡️ Batasan Akses

Admin tidak ikut trial class sebagai student (kecuali punya akun student terpisah).

Semua aksi CRUD (Create, Read, Update, Delete) di panel admin hanya bisa dilakukan user dengan role admin.



20. 🎓 Hak Akses & Fitur Student
1. Akses Course & Lesson

Melihat daftar course trial yang tersedia.

Melihat detail course (deskripsi, jumlah lesson, durasi).

Membuka lesson dalam course.

Membaca konten lesson (teks, gambar, resource).

Menonton video pembelajaran yang ter-embed.

Menandai lesson sebagai selesai.

2. Tes & Evaluasi

Mengikuti quiz/test yang terhubung dengan lesson atau course.

Menjawab soal pilihan ganda (MCQ), multiple answers, dan essay (jika ada).

Melihat hasil tes secara langsung (skor & jawaban benar/salah).

Mengulang quiz.

3. Progress Tracking

Melihat progress per course (persentase lesson selesai).

Melihat history tes yang sudah diikuti beserta skor.

Melihat apakah sudah lulus (pass score) atau belum.

4. Dashboard Student

Ringkasan course yang sedang diikuti.

Statistik pribadi: jumlah lesson selesai, skor rata-rata.

Rekomendasi lesson berikutnya yang harus dipelajari.

5. Profil & Akun

Mengubah data pribadi (nama, email, password).

Mengupload foto profil (opsional).

Logout dari aplikasi.

6. Notifikasi & Reminder (opsional)

Mendapat notifikasi jika ada quiz baru.

Reminder jika belum menyelesaikan lesson tertentu.

Email otomatis berisi hasil quiz / progress.

🛡️ Batasan Akses Student

Student hanya bisa mengakses trial course (tidak bisa membuat/mengedit course/lesson).

Tidak bisa melihat data user lain atau hasil tes orang lain.

Tidak punya akses ke panel admin.