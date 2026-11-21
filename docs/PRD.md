# 📋 PRD — Nizar Khan Portfolio (`folio-app`)
> Versi 1.0 · Disegerakkan 22 November 2026 · Pemilik: Nizar Khan · Status: Siap & Aktif

## 1. Ringkasan
- **Masalah:** Portfolio statik sukar dikemas kini apabila projek baharu, pengalaman kerja, atau kemahiran bertambah; selain itu tiada peti masuk berpusat untuk pertanyaan klien/majikan dan tiada pengoptimuman SEO berstruktur.
- **Penyelesaian:** Sistem web Personal Portfolio Single-Page bertema Cyberpunk HUD (Light & Dark Mode) dengan Admin CMS penuh berasaskan Laravel 13, Livewire 4, Flux UI, dan Tailwind CSS v4.
- **Cadangan Nilai:** Pelawat dan recruiter boleh melihat profil, maksimum 6 projek featured (serta katalog penuh *All Projects*), garis masa kerjaya, matriks kemahiran 4 kategori, dan menghantar mesej terus — manakala pemilik boleh mengemas kini semua kandungan secara masa nyata tanpa menyentuh kod.

## 2. Pengguna Sasaran
| Persona | Keperluan Utama | Ciri Paling Penting | Akses |
| :--- | :--- | :--- | :--- |
| **Pelawat / Recruiter / Klien** | Nilai kepakaran, lihat projek & pengalaman, hubungi pemilik | Hero identiti, kad projek + `Show Details`, borang Contact | Awam (`/`) |
| **Pemilik Portfolio (Nizar Khan)** | Kemas kini profil, projek, pengalaman, kemahiran & baca mesej | Admin CMS penuh + KPI Dashboard + Auth 2FA/Passkey | `/dashboard` & `/admin/*` |

## 3. Skop MVP
- **DALAM SKOP:** Halaman awam single-page (`Home`, `Projects`, `Experience`, `Skills`, `Contact`), modal `All Projects` + top-layer `Show Details`, Light & Dark mode, SEO penuh (JSON-LD, `/sitemap.xml`, `/robots.txt` dinamik), Admin CMS penuh untuk Profile/Hero, Projects (maks 6 featured, thumbnail upload), Experience & Skills, Message Inbox (Read/Unread + notifikasi e-mel), serta Auth terlindung (`/sys-console/login`, `/register` dimatikan).
- **LUAR SKOP:** Multi-user / multi-tenant (`user_id`), pendaftaran awam, blog/artikel panjang, integrasi pembayaran, atau aplikasi mudah alih natif.

## 4. Keperluan Fungsian
| ID | Pengguna boleh ... | Modul | Keutamaan | Status |
| :--- | :--- | :---: | :---: | :---: |
| `FR-01` | Kemas kini profil, headline, tagline, bio, status availability, e-mel anti-spam (`[at]`), avatar/banner/resume | 01 | Wajib | ✅ |
| `FR-02` | Full CRUD projek, muat naik thumbnail, kawal urutan `sort_order`, had maksimum 6 featured, pautan demo/repo | 02 | Wajib | ✅ |
| `FR-03` | Full CRUD pengalaman kerja & kemahiran mengikut 4 kategori (`backend`, `frontend`, `database`, `devops_tools`) | 03 | Wajib | ✅ |
| `FR-04` | Layari portfolio awam responsif (Light/Dark), buka `All Projects`/`Show Details`, hantar borang Contact & urus Inbox | 04 | Wajib | ✅ |

## 5. Keperluan Bukan Fungsian
| Kategori | Keperluan | Pelaksanaan |
| :--- | :--- | :--- |
| **Keselamatan** | Akses admin dilindungi; laluan login tidak terdedah di `/login` | Middleware `['auth', 'verified']`, prefix `sys-console`, `/register` dimatikan, rate limiting Fortify |
| **SEO & Indeks** | Laman awam diindeks maksimum oleh Google; admin disekat | JSON-LD `Person`/`ProfilePage`/`ItemList`, `/sitemap.xml`, `/robots.txt` dinamik, `noindex` pada admin/auth |
| **Responsif** | Paparan kemas pada mobile (`375px`), tablet (`768px`), desktop (`1280px+`) | Menu hamburger mobile (`md:hidden`), grid responsif, `break-all`/`break-words` |
| **Bahasa UI** | 100% Bahasa Inggeris profesional pada antaramuka web | Semua label, butang, dan mesej UI dalam Bahasa Inggeris |

## 6. Peranan & Kebenaran
| Peranan | Boleh Buat | Tidak Boleh Buat |
| :--- | :--- | :--- |
| **Pelawat Awam** | Lihat seksyen portfolio awam, buka modal projek, hantar borang Contact | Akses `/dashboard`, `/admin/*`, atau `/settings/*` (redirect ke `/sys-console/login`) |
| **Admin (Pemilik)** | Akses penuh `/dashboard`, `/admin/*`, `/settings/*` termasuk semua CRUD | Daftar akaun baharu secara awam (`/register` = 404) |

## 7. Metrik Kejayaan & Log Keputusan
- **Metrik:** 0 ralat Pint & PHPStan · 100% ujian feature & unit lulus · 0 horizontal overflow pada mobile/tablet/desktop.
- **Log Keputusan:**
  - `2026-11-13`: Maksimum 6 projek featured; modal `Show Details` dalam `All Projects` guna native `<dialog>` `showModal()`.
  - `2026-11-14`: `Public Contact Email` membenarkan format `[at]`; penukaran `[at]` → `@` dibuat secara dalaman semasa menghantar e-mel notifikasi.
  - `2026-11-21`: Laluan Fortify dipindahkan ke `/sys-console/login`; `/robots.txt` dan `/sitemap.xml` dijana secara dinamik; Light & Dark mode ditambah pada laman awam.
