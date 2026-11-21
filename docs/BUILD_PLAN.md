# 🛠️ PELAN SESI PEMBINAAN & GO-LIVE — Nizar Khan Portfolio (`folio-app`)

| Sesi | Modul / Fasa | Bergantung Pada | Status & Kriteria Siap |
| :--- | :--- | :--- | :--- |
| Sesi 1 | Modul 01: Profile, Hero & Dashboard (`01_profile_hero.md`) | Tiada | ✅ Siap — `ProfileEditorTest` & `ProfileSeeder` lulus |
| Sesi 2 | Modul 02: Projects Showcase (`02_projects.md`) | Modul 01 | ✅ Siap — `ProjectManagerTest` & `ProjectSeeder` lulus |
| Sesi 3 | Modul 03: Experience & Skills (`03_experience_skills.md`) | Modul 01 | ✅ Siap — `ExperienceSkillManagerTest` & 27 skills lulus |
| Sesi 4 | Modul 04: Public Portfolio, SEO & Inbox (`04_public_and_messages.md`) | Modul 01–03 | ✅ Siap — `PublicPortfolioAndMessagesTest` lulus |
| Sesi Akhir | Go-Live Production (`https://nizarkhan.dev` via Docker + Cloudflare Tunnel) | Modul 01–04 | ✅ Live — Docker, Resend SMTPS & GSC aktif |

## Prompt Setiap Sesi Penyelenggaraan (Salin & Gantikan Nama Modul)
```text
Baca @AGENT.md, @docs/STATUS.md, @docs/PRD.md dan @docs/ARCHITECTURE.md.
Hari ini kita kemas kini SATU modul sahaja: @docs/modules/02_projects.md.
Patuhi AGENT.md: 100% UI Bahasa Inggeris, tiada user_id pada jadual portfolio, tema Cyberpunk HUD (Light & Dark), native <dialog> untuk nested modal, dan Quality Gates (`npm run build`, `composer lint:check`, `composer types:check`, `php artisan test`).
Sebelum tulis kod: senaraikan fail yang akan dicipta/diubah & tunggu kelulusan saya.
Selepas siap: tunjuk keputusan Quality Gates serta kemas kini `docs/STATUS.md` dan `docs/ARCHITECTURE.md` jika berkaitan.
```

## Senarai Semak Go-Live (`nizarkhan.dev`)
- [x] `APP_ENV=production`, `APP_DEBUG=false`, `APP_URL=https://nizarkhan.dev`
- [x] Fail `public/hot` dikecualikan dalam `.dockerignore` & aset dibina dengan `npm run build` dalam `Dockerfile`
- [x] `MAIL_MAILER=smtp` (`smtp.resend.com`, port `465`, `MAIL_SCHEME=smtps`) + pengesahan DNS DKIM/SPF
- [x] `php artisan storage:link` & auto-seeding `database/seeders/media/` melalui `docker/entrypoint.sh`
- [x] `$middleware->trustProxies(at: '*')` dalam `bootstrap/app.php` untuk Cloudflare Tunnel HTTPS
- [x] Hantar `https://nizarkhan.dev/sitemap.xml` & Request Indexing di Google Search Console
- [ ] Tukar kata laluan akaun admin lalai & aktifkan 2FA / Passkey di `/settings/*`
