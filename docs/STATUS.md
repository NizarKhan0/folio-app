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
- **Siap & Live (`https://nizarkhan.dev`):**
  - Tema Cyberpunk HUD diseragamkan pada Public (Light & Dark), Auth (`/sys-console/login`, mod gelap fixed screen), Sidebar, Dashboard, dan kesemua 4 modul Admin (aksen Cyan).
  - SEO lengkap (`<title>` 55 aksara *Nizar Khan — Fullstack Laravel & PHP Developer Malaysia*, meta description, canonical, Open Graph, Twitter Card, JSON-LD `Person`/`ProfilePage`/`ItemList`, `/sitemap.xml`, `/robots.txt` dinamik, favicon Pilihan 2).
  - Data terkini disegerakkan: 3 projek (*Personal Portfolio*, *LifeOS*, *Kanban PMO*), 3 pengalaman kerja, 27 skills, serta penukaran `[at]` → `@` untuk notifikasi e-mel Contact.
  - Docker produksi (`Dockerfile` 3-stage, `docker-compose.yml` port `9002`, `docker/entrypoint.sh`, `database/seeders/media/`) & `$middleware->trustProxies(at: '*')` dalam `bootstrap/app.php` aktif di `/opt/folio-app`.
  - Resend SMTP (`smtp.resend.com`, port `465`, `MAIL_SCHEME=smtps`), pengesahan domain Cloudflare di **Google Search Console**, penghantaran `https://nizarkhan.dev/sitemap.xml`, dan **Request Indexing** selesai.
- **Isu Terbuka:** Tiada isu teknikal.

## Langkah Seterusnya
1. Kemas kini pautan **LinkedIn**, **Resume PDF**, dan **Demo URL** projek dari panel admin apabila sudah sedia.
2. Pantau prestasi carian & indeks di Google Search Console.
