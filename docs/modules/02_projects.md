# Modul 02: Projects Showcase Manager
> Status: ✅ Siap · Disegerakkan 22 November 2026
- Bergantung pada: Modul 01 · Menyediakan untuk: Modul 04 · Keperluan PRD: `FR-02`

## 1. Tujuan
Mengurus senarai projek portfolio (`Project`) lengkap dengan Full CRUD, kawalan urutan `sort_order`, muat naik thumbnail, tag `tech_stack`, pautan demo/repo bebas format (membenarkan `#` atau URL), serta had maksimum **6 projek featured**.

## 2. Data (Migration & Model)
- Jadual `projects` (Tiada `user_id`):
  - `id`, `title` (string), `slug` (string, unik), `description` (text), `tech_stack` (json), `thumbnail_path` (string, nullable), `demo_url` (string, nullable), `repo_url` (string, nullable), `is_featured` (boolean, default: `true`), `is_published` (boolean, default: `true`), `sort_order` (integer, default: `0`), `timestamps`.
  - Indeks: `['is_published', 'sort_order']`.
- Model `Project`:
  - Cast `tech_stack` sebagai `array`; `is_featured` & `is_published` sebagai `boolean`.
  - Scope `published()` disusun mengikut `sort_order ASC, id ASC` dan helper `thumbnailUrl(): ?string`.

## 3. Logik (`ProjectManager.php`)
- Had maksimum **6** projek `is_featured = true` dikuatkuasakan semasa `save()` dan `toggleFeatured()`.
- Validasi `demo_url` dan `repo_url` menggunakan `['nullable', 'string', 'max:500']` supaya nilai `#` atau teks laluan dalaman dibenarkan.
- Urutan `Move Up` / `Move Down` tanpa drag-and-drop.

## 4. UI / API (Full CRUD)
- Route: `GET /admin/projects` (`ProjectManager::class`).
- Bahasa UI: 100% Bahasa Inggeris profesional (`02 // Showcase`, `Add Project`, `Tech Stack`, `Demo →`, `Delete Project`).

### 4a. Senarai Skrin
| Skrin / Modal | Route | Siapa | Elemen Utama | Tindakan |
| :--- | :--- | :--- | :--- | :--- |
| Projects Manager | `/admin/projects` | Admin | Kad metrik (Total, Published, Featured), jadual projek + susunan | Add, Edit, Move Up/Down, Toggle, Delete |
| Modal Add / Edit Project | — (Modal) | Admin | Title, slug, description, tech stack chips, demo/repo (`text`), thumbnail | Save, Cancel |
| Modal Delete Project | — (Modal) | Admin | Amaran pengesahan pemadaman rekod & thumbnail | Confirm Delete, Cancel |

### 4b. Wireframe Ringkas (`/admin/projects`)
```text
+------------------------------------------------------------------------+
| 02 // SHOWCASE                                      [ + Add Project ]  |
| [Total: 3] · [Published: 3] · [Featured: 3 / 6]                        |
|------------------------------------------------------------------------|
| #  Project                           Stack        Status    Actions    |
| 1  Personal Portfolio Website        Laravel...   Published [Edit][Del]|
| 2  LifeOS — Personal Command Center  Laravel...   Published [Edit][Del]|
| 3  Kanban PMO — Project Command Ctr  Laravel...   Published [Edit][Del]|
+------------------------------------------------------------------------+
```

## 5. Ujian & Seeder
- `ProjectManagerTest`: Full CRUD, had maksimum 6 featured, pautan bukan URL (`#`, `private-repo`), susunan `sort_order`, dan `ProjectSeeder`.
- `ProjectSeeder`:
  1. **Personal Portfolio Website** (`personal-portfolio-website`, `sort_order: 1`)
  2. **LifeOS — Personal Command Center** (`life-os`, `sort_order: 2`)
  3. **Kanban PMO — Project Command Center** (`kanban-pmo`, `sort_order: 3`)

## 6. Acceptance Criteria
- [x] Pemilik boleh melakukan Full CRUD projek, tukar urutan, dan memuat naik thumbnail.
- [x] Had maksimum 6 projek featured dikuatkuasakan dan `ProjectManagerTest` lulus.
