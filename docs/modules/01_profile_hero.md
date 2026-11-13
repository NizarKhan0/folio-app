# Modul 01: Profile, Hero Settings & Dashboard Overview
Rujuk `AGENT.md` dan `docs/ARCHITECTURE.md`. Bina **Modul 01: Profile, Hero Settings & Dashboard Overview** di atas Livewire Starter Kit:

### 1. Migration & Schema (`profiles` — Tiada `user_id`)
- `profiles`: `id`, `name` (string, default: 'Nizar Khan'), `headline` (string, default: 'Hi, I\'m Nizar Khan'), `tagline` (string, default: 'Fullstack PHP Developer — Laravel, Livewire, Filament, DevOps'), `bio` (text, nullable), `location` (string, default: 'Putrajaya, Malaysia'), `is_available` (boolean, default: true), `email` (string, nullable), `github_url` (string, nullable), `linkedin_url` (string, nullable), `resume_url` (string, nullable), `timestamps`.

### 2. Model & Business Logic (`Profile.php`)
- Cast `is_available` sebagai `boolean`.
- Sediakan helper statik `Profile::current(): self` yang memanggil `self::firstOrCreate(['id' => 1], [...])`.

### 3. Livewire UI (`app/Livewire/Admin/ProfileEditor.php` & Sidebar Starter Kit)
- Gunakan layout Starter Kit sedia ada (`components.layouts.app`).
- Kemas kini `resources/views/components/layouts/app/sidebar.blade.php` untuk menambah pautan navigasi `Profile & Hero`.
- **Admin UI:** Borang reaktif kemas kini maklumat Hero (`name`, `headline`, `tagline`, `bio`, `location`), pautan GitHub/LinkedIn/Resume, dan toggle satu-klik `is_available` tanpa reload.

### 4. Database Seeder (`ProfileSeeder.php` — Data Sebenar me.zaryue.com)
- Pastikan `DatabaseSeeder` turut mencipta 1 akaun Admin lalai (`nizar@zaryue.com`) untuk log masuk ke Starter Kit.
- Jana 1 rekod `Profile` (`id = 1`):
  - `name`: `'Nizar Khan'`
  - `headline`: `'Hi, I\'m Nizar Khan'`
  - `tagline`: `'Fullstack PHP Developer — Laravel, Livewire, Filament, DevOps'`
  - `bio`: `'Fullstack PHP Developer experienced in building custom Laravel & Livewire applications, migrating legacy digital assets with 100% data integrity, optimizing MySQL queries, and managing production deployments.'`
  - `location`: `'Putrajaya, Malaysia'`
  - `is_available`: `true`