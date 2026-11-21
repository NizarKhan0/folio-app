# 📍 STATUS PROJEK — Nizar Khan Portfolio (`folio-app`)
> Dikemas kini: 22 November 2026 (Semua Modul Teras & Penambahbaikan Siap)

## Modul Pembinaan
| # | Modul | Fail Spesifikasi | Status | Bergantung Pada | Nota |
| :---: | :--- | :--- | :---: | :--- | :--- |
| 01 | Profile, Hero Settings & Dashboard | `docs/modules/01_profile_hero.md` | ✅ Siap | Tiada | Menyokong avatar/banner/resume, `[at]` e-mel, & KPI Cyberpunk |
| 02 | Projects Showcase Manager | `docs/modules/02_projects.md` | ✅ Siap | Modul 01 | 3 projek aktif (*Personal Portfolio*, *LifeOS*, *Kanban PMO*) |
| 03 | Experience & Skills Manager | `docs/modules/03_experience_skills.md` | ✅ Siap | Modul 01 | 3 pengalaman kerja & 27 skills terkini |
| 04 | Public Portfolio, SEO & Message Inbox | `docs/modules/04_public_and_messages.md` | ✅ Siap | Modul 01, 02, 03 | Light/Dark mode, JSON-LD SEO, `/sitemap.xml`, `/robots.txt` |

## Sesi Terakhir
- **Siap:**
  - Tema Cyberpunk HUD diseragamkan pada Public (Light & Dark), Auth (`/sys-console/login`, mod gelap fixed screen), Sidebar, Dashboard, dan kesemua 4 modul Admin (aksen Cyan).
  - SEO lengkap (`<title>`, meta description, canonical, Open Graph, Twitter Card, JSON-LD `Person`/`ProfilePage`/`ItemList`, `/sitemap.xml`, `/robots.txt` dinamik, favicon Pilihan 2).
  - Data terkini disegerakkan: 3 projek (*Personal Portfolio*, *LifeOS*, *Kanban PMO*), 3 pengalaman kerja, 27 skills (`Yii2`, `Flux UI`, `SQLite`, `Git`, `GitHub Actions`, `Linux / Ubuntu`, `Claude`, `OpenCode`, `Antigravity`, dll.), serta penukaran `[at]` → `@` untuk notifikasi e-mel Contact.
- **Isu Terbuka:** Tiada isu kod. (Jika hendak aktifkan e-mel keluar ke Gmail pada produksi, isi tetapan SMTP dalam `.env` pelayan).

## Langkah Seterusnya (Go-Live)
1. Sediakan domain (`nizarkhan.dev`) dengan HTTPS dan tetapkan `APP_ENV=production`, `APP_DEBUG=false`, `APP_URL=https://nizarkhan.dev` dalam `.env` pelayan.
2. Jalankan `npm run build`, `php artisan migrate --force`, dan `php artisan storage:link`.
3. Daftar domain di Google Search Console dan hantar `https://nizarkhan.dev/sitemap.xml`.
