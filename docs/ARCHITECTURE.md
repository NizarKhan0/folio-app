# 🏛️ Nizar Khan Portfolio — Master Architecture (Livewire Starter Kit Edition)

## 1. Technical Overview
- Base: Official Laravel 13 Livewire Starter Kit (Auth, 2FA, Settings, & Sidebar Layout built-in).
- Database: MySQL 8.0+ (InnoDB, utf8mb4_unicode_ci) — 5 Jadual Portfolio Teras Tanpa `user_id`.
- Architecture:
  1. Public Single-Page Portfolio (`/`): Menggantikan `welcome` route dengan `App\Livewire\PublicPortfolio` (Tema Cyberpunk HUD; Seksyen Home, Projects [maks 6 featured dalam grid 3-lajur + modal `All Projects` + native top-layer `<dialog>` `Show Details`], Experience, Skills, Contact, & menu mobile).
  2. Admin Dashboard (`/dashboard` & `/admin/*`): Menggunakan layout `resources/views/layouts/app.blade.php` (`layouts::app`) dan sidebar `resources/views/layouts/app/sidebar.blade.php` daripada Livewire Starter Kit.

## 2. Database Schema Overview (5 Standalone Core Tables)
1. `profiles`    — Rekod tunggal (Singleton `id = 1`) menyimpan nama, headline, tagline, bio, lokasi, status availability, e-mel, pautan sosial, `resume_url`/`resume_path`, `avatar_path`, dan `banner_path`.
2. `projects`    — Menyimpan projek portfolio, deskripsi, `tech_stack` (JSON), pautan demo/repo, `thumbnail_path`, `is_featured`, `is_published`, dan `sort_order`.
3. `experiences` — Menyimpan sejarah kerja (role, company, location, period, description, `tech_stack` JSON, dan `sort_order`).
4. `skills`      — Menyimpan kemahiran teknikal (`name`, `category`, `sort_order`).
5. `messages`    — Menyimpan mesej daripada pelawat di borang Contact (`name`, `email`, `subject`, `message`, `is_read`).

## 3. Peta Laluan & Komponen Livewire (`routes/web.php`)
- `GET /`                       -> `App\Livewire\PublicPortfolio::class` (`name('home')`)
- `GET /dashboard`              -> Ringkasan KPI Portfolio (Total Projects, Experiences, Skills, Unread Messages) di dalam layout Starter Kit.
- `GET /admin/profile`          -> `App\Livewire\Admin\ProfileEditor::class` (`name('admin.profile')`)
- `GET /admin/projects`         -> `App\Livewire\Admin\ProjectManager::class` (`name('admin.projects')`)
- `GET /admin/experience-skills`-> `App\Livewire\Admin\ExperienceSkillManager::class` (`name('admin.experience-skills')`)
- `GET /admin/messages`         -> `App\Livewire\Admin\MessageInbox::class` (`name('admin.messages')`)
- Laluan `/settings/*` daripada Livewire Starter Kit dikekalkan sepenuhnya.

## 4. Verification & Build Commands
- Asset Build: `npm run build` (Vite + Tailwind CSS v4)
- Code Style: `composer lint:check` (Laravel Pint)
- Static Analysis: `composer types:check` (Larastan / PHPStan)
- Test Suite: `php artisan test` (PHPUnit) atau `composer test` (menjalankan kesemuanya)