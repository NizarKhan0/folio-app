# 🤖 AGENT RULES & BOUNDARIES: Nizar Khan Portfolio (Livewire Starter Kit)

## 1. Project Context & Starter Kit State
- Projek Laravel 13 ini dipasang menggunakan **Official Laravel Livewire Starter Kit**.
- DILARANG menjalankan `laravel new`, memadam direktori teras, memadam `.env`, atau merosakkan laluan Auth & Settings sedia ada (`/login`, `/dashboard`, `/settings/*`).
- Bina dan selenggara ciri mengikut spesifikasi di `docs/modules/` dan `docs/ARCHITECTURE.md`.

## 2. Technical Stack Lock (DILARANG UBAH)
- Framework: PHP 8.3+ / Laravel 13 (`declare(strict_types=1);`).
- Frontend: Pure Livewire v4 + komponen rasmi Livewire Starter Kit (Flux UI + Alpine.js sedia ada dalam Livewire).
- DILARANG: Memasang Filament, Inertia, Vue, React, atau Bootstrap.
- Styling: Pure Tailwind CSS v4 (`@import "tailwindcss";` dalam `resources/css/app.css`).
- Database: MySQL 8.0+ (InnoDB).

## 3. Single-Owner Architecture & Starter Kit Sidebar Integration
- Sistem ini adalah Personal Portfolio milik seorang pengguna sahaja (Nizar Khan).
- DILARANG meletakkan lajur `user_id` pada jadual `profiles`, `projects`, `experiences`, `skills`, atau `messages`.
- Semua halaman pengurusan Admin WAJIB menggunakan layout sidebar Starter Kit sedia ada (`resources/views/layouts/app.blade.php`) dan dilindungi oleh middleware `['auth', 'verified']`.
- Kemas kini menu navigasi di `resources/views/layouts/app/sidebar.blade.php` supaya memaparkan pautan ke 4 modul portfolio.

## 4. Web UI Language: 100% Professional English
- SEMUA tajuk, navigasi (`Home`, `Projects`, `Experience`, `Skills`, `Contact`), label borang, teks butang, modal, dan toast WAJIB 100% Bahasa Inggeris.

## 5. Mandatory Full CRUD on Admin Panel
- Setiap modul di dalam Admin Panel WAJIB mempunyai:
  1. Create & Update: Modal borang reaktif pantas tanpa reload.
  2. Read: Kad ringkasan KPI & Jadual/Grid kemas berserta urutan `sort_order`.
  3. Delete: Butang Delete berserta Confirmation Modal keselamatan.

## 6. UI, Modal & Responsive Guardrails (Vibe-Coding Rules)
- **Public Aesthetic:** Tema Cyberpunk HUD gelap (`#06070B` / `#0A0D16`) dengan aksen neon `cyan-400`, `fuchsia-500`, dan `yellow-300`.
- **Nested Modals:** JANGAN letak `position: fixed; inset: 0;` di dalam `<flux:modal>` (kerana Flux menggunakan `<dialog>` yang mengehadkan bounding/backdrop anak). Untuk dialog bertingkat (seperti `Show Details` dalam `All Projects`), guna elemen native top-layer `<dialog>` yang dibuka dengan `.showModal()`, berpusat (`m-auto`), `backdrop:*` penuh viewport, dan kad dalaman `max-h-[90vh] overflow-y-auto`.
- **Responsiveness:** Semua halaman awam WAJIB berfungsi kemas pada mobile (`375px`), tablet (`768px`), dan desktop (`1280px+`) tanpa horizontal overflow — termasuk menu hamburger mobile (`md:hidden`), `flex-wrap`, serta `break-words`/`break-all` untuk teks panjang (e-mel, slug, URL).
- **Verification Gate:** Selepas setiap perubahan kod atau seeder, WAJIB jalankan `npm run build` (jika ubah Blade/CSS/JS), `composer lint:check`, `composer types:check`, dan `php artisan test`.