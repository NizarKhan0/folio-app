# 🤖 AGENT RULES & BOUNDARIES: Nizar Khan Portfolio (Livewire Starter Kit)

## 1. Project Context & Starter Kit State
- Projek Laravel 13 ini dipasang menggunakan **Official Laravel Livewire Starter Kit**.
- DILARANG menjalankan `laravel new`, memadam direktori teras, memadam `.env`, atau merosakkan laluan Auth & Settings sedia ada (`/login`, `/dashboard`, `/settings/*`).
- Bina dan selenggara ciri mengikut spesifikasi di `docs/modules/` dan `docs/ARCHITECTURE.md`.

## 2. Technical Stack Lock (DILARANG UBAH)
- Framework: PHP 8.3+ / Laravel 13 (`declare(strict_types=1);`).
- Frontend: Pure Livewire v4 + komponen rasmi Livewire Starter Kit (Flux UI / Blade Components sedia ada).
- DILARANG: Memasang Filament, Inertia, Vue, React, atau Bootstrap.
- Styling: Pure Tailwind CSS v4 (`@import "tailwindcss";` dalam `resources/css/app.css`).
- Database: MySQL 8.0+ (InnoDB).

## 3. Single-Owner Architecture & Starter Kit Sidebar Integration
- Sistem ini adalah Personal Portfolio milik seorang pengguna sahaja (Nizar Khan).
- DILARANG meletakkan lajur `user_id` pada jadual `profiles`, `projects`, `experiences`, `skills`, atau `messages`.
- Semua halaman pengurusan Admin WAJIB menggunakan layout sidebar Starter Kit sedia ada dan dilindungi oleh middleware `['auth', 'verified']`.
- Kemas kini menu navigasi di `resources/views/components/layouts/app/sidebar.blade.php` supaya memaparkan pautan ke 4 modul portfolio.

## 4. Web UI Language: 100% Professional English
- SEMUA tajuk, navigasi (`Home`, `Projects`, `Experience`, `Skills`, `Contact`), label borang, teks butang, modal, dan toast WAJIB 100% Bahasa Inggeris.

## 5. Mandatory Full CRUD on Admin Panel
- Setiap modul di dalam Admin Panel WAJIB mempunyai:
  1. Create & Update: Modal borang reaktif pantas tanpa reload.
  2. Read: Kad ringkasan KPI & Jadual/Grid kemas berserta urutan `sort_order`.
  3. Delete: Butang Delete berserta Confirmation Modal keselamatan.