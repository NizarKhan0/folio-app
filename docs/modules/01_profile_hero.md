# Modul 01: Profile, Hero Settings & Dashboard Overview
Rujuk `AGENT.md` dan `docs/ARCHITECTURE.md`. Bina **Modul 01: Profile, Hero Settings & Dashboard Overview** di atas Livewire Starter Kit:

### 1. Migration & Schema (`profiles` — Tiada `user_id`)
- `profiles`: `id`, `name` (string, default: 'Nizar Khan'), `headline` (string, default: 'Hi, I\'m Nizar Khan'), `tagline` (string), `bio` (text, nullable), `location` (string), `is_available` (boolean, default: true), `email` (string, nullable), `github_url` (string, nullable), `linkedin_url` (string, nullable), `resume_url` (string, nullable), `resume_path` (string, nullable), `avatar_path` (string, nullable), `banner_path` (string, nullable), `timestamps`.

### 2. Model & Business Logic (`Profile.php`)
- Cast `is_available` sebagai `boolean`.
- Sediakan helper statik `Profile::current(): self` yang memanggil `self::firstOrCreate(['id' => 1], [...])`.
- Sediakan helper `avatarUrl(): ?string`, `bannerUrl(): ?string`, dan `resolvedResumeUrl(): ?string` untuk paparan aset daripada `public` storage disk.

### 3. Livewire UI (`app/Livewire/Admin/ProfileEditor.php` & Sidebar Starter Kit)
- Gunakan layout Starter Kit sedia ada (`resources/views/layouts/app.blade.php`).
- Kemas kini `resources/views/layouts/app/sidebar.blade.php` untuk menambah pautan navigasi `Profile & Hero`.
- **Admin UI:** Borang reaktif kemas kini maklumat Hero (`name`, `headline`, `tagline`, `bio`, `location`, `email`), pautan GitHub/LinkedIn/Resume, muat naik `avatar`/`banner`/`resume` (`WithFileUploads`), dan toggle satu-klik `is_available` tanpa reload.

### 4. Database Seeder (`ProfileSeeder.php` — Data Sebenar Terkini)
- Pastikan `DatabaseSeeder` turut mencipta 1 akaun Admin lalai (`test@example.com`, password: `password`) untuk log masuk ke Starter Kit.
- Jana 1 rekod `Profile` (`id = 1`):
  - `name`: `'Nizar Khan'`
  - `headline`: `'Hi, I\'m Nizar Khan'`
  - `tagline`: `'Fullstack PHP Developer · Laravel, Livewire & Filament'`
  - `bio`: `'I design and ship reliable web platforms, admin dashboards, and automated workflows — turning complex business processes and legacy databases into fast, production-ready systems.'`
  - `location`: `'Kuala Lumpur, Malaysia'`
  - `email`: `'nizarkhan.digital@gmail.com'`
  - `github_url`: `'https://github.com/NizarKhan0'`
  - `is_available`: `true`