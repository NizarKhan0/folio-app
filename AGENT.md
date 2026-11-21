# 🤖 AGENT RULES & BOUNDARIES: Nizar Khan Portfolio (`folio-app`)
> Baca SEPENUHNYA sebelum menulis kod. Disegerakkan dengan sistem pada 22 November 2026.
> Kod ialah kebenaran: jika kod & dokumen bercanggah, semak kod, kemudian kemas kini dokumen.
> Jika permintaan bercanggah dengan fail ini: BERHENTI, tanya, kemudian KEMAS KINI peraturan ini.

## 1. Project Context
- Projek Laravel 13 ini SUDAH DIPASANG menggunakan **Official Laravel Livewire Starter Kit**.
- DILARANG menjalankan `laravel new`, memadam direktori teras, memadam `.env`, atau merosakkan laluan Auth & Settings sedia ada (`/sys-console/login`, `/dashboard`, `/settings/*`).
- Rujukan: `docs/PRD.md` · `docs/ARCHITECTURE.md` · `docs/modules/01..04.md` · `docs/STATUS.md` · `docs/BUILD_PLAN.md` · `docs/diagrams.md`

## 2. Technical Stack Lock (DILARANG UBAH)
- Bahasa & framework: PHP 8.4 + Laravel 13.x (`declare(strict_types=1);` wajib dalam semua fail PHP).
- UI & styling: Pure Livewire v4 + Flux UI + Alpine.js sedia ada dalam Livewire + Pure Tailwind CSS v4 (`@import "tailwindcss";` dalam `resources/css/app.css`).
- Database: MySQL 8.0+ (InnoDB) · Dokumentasi rasmi: https://laravel.com/docs/13.x (guna sintaks TERKINI).
- DILARANG pakej / library tambahan: Filament, Inertia, Vue, React, Bootstrap, jQuery, atau Moment.js tanpa izin eksplisit.

## 3. UI Language & Theme Policy
- **Bahasa UI Web:** SEMUA tajuk, navigasi (`Home`, `Projects`, `Experience`, `Skills`, `Contact`), label borang, teks butang, modal, dan toast WAJIB **100% Bahasa Inggeris profesional**.
- **Tema Awam & Admin:** Cyberpunk HUD (`#06070B` / `#0A0D16` dengan aksen neon `cyan`, `fuchsia`, `yellow`) bersama sokongan **Light & Dark Mode** (`$flux.appearance`, lalai `dark` pada laman awam).
- **Auth Layout (`resources/views/layouts/auth/simple.blade.php`):** Dikunci ke mod gelap (`dark`) + `h-dvh overflow-hidden` tanpa scrollbar serta `<meta name="robots" content="noindex, nofollow" />`.

## 4. Mandatory Full CRUD, Modals & Responsive UX
- Setiap modul Admin WAJIB mempunyai:
  1. Create & Update: Modal borang reaktif pantas tanpa reload.
  2. Read: Kad ringkasan KPI & Jadual/Grid kemas berserta urutan `sort_order`.
  3. Delete: Butang Delete berserta Confirmation Modal keselamatan yang menyatakan kesan pemadaman.
- **Nested Modals:** JANGAN letak `position: fixed; inset: 0;` di dalam `<flux:modal>`. Untuk dialog bertingkat (seperti `Show Details` dalam `All Projects`), guna elemen native top-layer `<dialog>` yang dibuka dengan `.showModal()`, berpusat (`m-auto`), `backdrop:*` penuh viewport, dan kad dalaman `max-h-[90vh] overflow-y-auto`.
- **Responsiveness:** Semua halaman awam WAJIB kemas pada mobile (`375px`), tablet (`768px`), dan desktop (`1280px+`) tanpa horizontal overflow — termasuk menu hamburger mobile (`md:hidden`), `flex-wrap`, dan `break-words`/`break-all`.

## 5. Single-Owner Architecture, Roles & Security
- Sistem ini adalah Personal Portfolio milik seorang pengguna sahaja (**Nizar Khan**).
- DILARANG meletakkan lajur `user_id` pada jadual `profiles`, `projects`, `experiences`, `skills`, atau `messages`.
- Semua halaman pengurusan Admin WAJIB menggunakan layout sidebar Starter Kit (`resources/views/layouts/app.blade.php` & `resources/views/layouts/app/sidebar.blade.php`) dan dilindungi middleware `['auth', 'verified']`.
- Laluan Auth menggunakan prefix `sys-console` (`/sys-console/login`; pendaftaran awam `/register` dimatikan dalam `config/fortify.php`).
- `Public Contact Email` menyokong format anti-spam `[at]` tanpa paksaan validasi e-mel; penukaran `[at]` → `@` dibuat secara dalaman semasa penghantaran notifikasi e-mel (`rescue()`).

## 6. Performance & SEO
- Asset imej awam menggunakan `fetchpriority="high"` pada avatar Hero dan `loading="lazy"` pada kad projek.
- SEO Google lengkap pada `resources/views/layouts/public.blade.php` (meta description, canonical, Open Graph, Twitter Card, JSON-LD `WebSite`/`ProfilePage`/`Person`/`ItemList`), serta laluan dinamik `GET /sitemap.xml` dan `GET /robots.txt` (tiada `public/robots.txt` statik).
- Semua halaman `/admin/*` dan `/sys-console/*` mempunyai `noindex, nofollow`.

## 7. Configuration & Assets
- Pemboleh ubah persekitaran daripada `.env` (`APP_URL`, `MAIL_*`, `DB_*`).
- Favicon rasmi (`public/favicon.svg`, `public/favicon.ico`, `public/apple-touch-icon.png`) dipautkan dalam `layouts/public.blade.php` dan `partials/head.blade.php`.

## 8. Guardrails
- DILARANG jalankan `composer require`, `npm install`, `migrate:fresh`, `db:wipe`, `git reset`, atau `git checkout` tanpa izin eksplisit.
- Semua seeder (`ProfileSeeder`, `ProjectSeeder`, `ExperienceSkillSeeder`, `MessageSeeder`) WAJIB idempotent (`updateOrCreate` / `firstOrCreate`).

## 9. SOP Kerja (4 Lapisan)
1. Baca `AGENT.md` + `docs/STATUS.md` + `docs/ARCHITECTURE.md` + nota modul.
2. Senaraikan fail yang akan dicipta/diubah; spesifikasi kabur -> TANYA.
3. Bina: Migration & Model -> Service / Logik -> Livewire & Blade -> Seeder & Feature Test.

## 10. Quality Gates
- Selepas setiap perubahan kod atau seeder, WAJIB jalankan dan lulus:
  1. `npm run build` (jika ubah Blade/CSS/JS)
  2. `composer lint:check` (`pint --parallel --test`)
  3. `composer types:check` (`phpstan analyse`)
  4. `php artisan test` (`phpunit`)
