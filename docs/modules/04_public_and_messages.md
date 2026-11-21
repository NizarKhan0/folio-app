# Modul 04: Public Single-Page Portfolio, SEO & Contact Inbox
> Status: ✅ Siap · Disegerakkan 22 November 2026
- Bergantung pada: Modul 01, 02, 03 · Menyediakan untuk: Go-Live · Keperluan PRD: `FR-04`

## 1. Tujuan
Menyediakan laman portfolio awam single-page bertema Cyberpunk HUD (Light & Dark Mode) yang responsif dan dioptimumkan untuk Google SEO, serta peti masuk mesej (`MessageInbox`) untuk mengurus pertanyaan pelawat.

## 2. Data (Migration & Model)
- Jadual `messages` (Tiada `user_id`):
  - `id`, `name` (string), `email` (string), `subject` (string, nullable), `message` (text), `is_read` (boolean, default: `false`), `timestamps`. Indeks: `['is_read', 'created_at']`.
- Model `Message`: cast `is_read` sebagai `boolean`, scope `unread()`.

## 3. Logik (`PublicPortfolio.php` & `MessageInbox.php`)
- **Public (`/`):**
  - Memaparkan Hero, maks 6 projek featured (`take(6)`), katalog penuh `All Projects` (`<flux:modal>`), dialog butiran `Show Details` (native top-layer `<dialog x-ref="detailDialog">` dengan `showModal()`), garis masa Experience boleh kembang, matriks Skills dengan penapis kategori, dan borang Contact.
  - `submitContact()` menyimpan ke jadual `messages` dan menukar `[at]` → `@` pada `Profile.email` sebelum menghantar e-mel melalui `Mail::raw()` dalam `rescue()`.
- **SEO & Layout (`public.blade.php` & `routes/web.php`):**
  - `<title>` bersih, meta description, keywords, canonical, Open Graph, Twitter Card, JSON-LD (`WebSite`, `ProfilePage`, `Person`, `ItemList`), `GET /sitemap.xml`, dan `GET /robots.txt` dinamik.
- **Inbox (`/admin/messages`):**
  - Papar kiraan unread, buka modal baca penuh, toggle Read/Unread, dan Delete dengan Confirmation Modal.

## 4. UI / API
- Route Awam: `GET /` (`PublicPortfolio::class`), `GET /sitemap.xml`, `GET /robots.txt`.
- Route Admin: `GET /admin/messages` (`MessageInbox::class`).

### 4a. Senarai Skrin
| Skrin / Modal | Route | Siapa | Elemen Utama | Tindakan |
| :--- | :--- | :--- | :--- | :--- |
| Public Portfolio | `/` | Awam | Navbar + scroll progress bar + toggle tema + menu mobile, Hero, Projects, Experience, Skills, Contact | Navigasi, Tukar Tema, Modal, Hantar Mesej |
| All Projects & Detail | — (Modal + `<dialog>`) | Awam | Grid semua projek & top-layer centered detail dialog | Show Details, Close |
| Message Inbox | `/admin/messages` | Admin | KPI Unread/Read, senarai mesej, modal baca penuh | View, Toggle Read/Unread, Delete |

### 4b. Wireframe Ringkas (`/`)
```text
+------------------------------------------------------------------------+
| [NK] NIZAR KHAN       [Home][Projects][Experience][Skills][Contact][☀] |
| ====== scroll progress neon bar ====================================== |
| [Available for Work] [NODE // Kuala Lumpur]  | NETRUNNER_ID // 001     |
| Hi, I'm Nizar Khan                           | [Avatar] Nizar Khan     |
| >_ Fullstack PHP Developer...                | [3 Proj][3 Role][27 Skl]|
|------------------------------------------------------------------------|
| 01 // DEPLOYED_SYSTEMS (Grid 3-Lajur, Maks 6 Featured + [All Projects])|
| 02 // CAREER_TIMELINE  (Garis Masa 3 Pengalaman Boleh Kembang)         |
| 03 // CAPABILITY_MATRIX (Penapis Kategori + 4 Kad Kategori Skills)     |
| 04 // COMMS_CHANNEL    (Info Lokasi/E-mel [at] + Borang Contact)       |
+------------------------------------------------------------------------+
```

## 5. Ujian & Seeder
- `PublicPortfolioAndMessagesTest`: Paparan semua seksyen, had 6 featured + modal `All Projects`, penapis kategori skills, SEO metadata & JSON-LD, `/sitemap.xml`, `/robots.txt` dinamik, `/sys-console/login` (`noindex` & `/login` = 404), penghantaran borang Contact dengan e-mel `[at]`, serta operasi Inbox.
- `MessageSeeder`: 2 contoh mesej (`1` unread, `1` read).

## 6. Acceptance Criteria
- [x] Laman awam responsif pada mobile, tablet, & desktop dengan Light & Dark Mode.
- [x] SEO Google (JSON-LD, `/sitemap.xml`, `/robots.txt` dinamik) & borang Contact berfungsi dan lulus semua Quality Gates.
