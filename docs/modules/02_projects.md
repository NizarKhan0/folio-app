# Modul 02: Projects Showcase Manager
Rujuk `AGENT.md` dan `docs/ARCHITECTURE.md`. Bina **Modul 02: Projects Showcase Manager** (100% English Web UI & Full CRUD):

### 1. Migration & Schema (`projects` — Tiada `user_id`)
- `projects`: `id`, `title` (string), `slug` (string, unique), `description` (text), `tech_stack` (json, cth: `["Livewire", "Tailwind CSS", "Laravel", "MySQL"]`), `thumbnail_path` (string, nullable), `demo_url` (string, nullable), `repo_url` (string, nullable), `is_featured` (boolean, default: true), `is_published` (boolean, default: true), `sort_order` (integer, default: 0), `timestamps`. Indeks: `['is_published', 'sort_order']`.

### 2. Model & Business Logic (`Project.php`)
- Cast `tech_stack` sebagai `array`, serta `is_featured` dan `is_published` sebagai `boolean`.
- Scope `published()` disusun mengikut `sort_order ASC`.

### 3. Livewire UI (`app/Livewire/Admin/ProjectManager.php`)
- Gunakan layout sidebar Livewire Starter Kit dan tambah menu `Projects` pada sidebar.
- **UI Language:** 100% Professional English ('Projects', 'Add Project', 'Tech Stack', 'Demo →', 'Delete Project').
- **MANDATORY Full CRUD:**
  1. **Create:** Modal tambah projek baharu berserta input tag `tech_stack` (comma-separated atau tag input), pautan demo/repo, dan muat naik imej thumbnail (`WithFileUploads`).
  2. **Read:** Kad metrik ringkas & jadual senarai projek di Admin Panel.
  3. **Update:** Butang Edit mengisi modal sedia ada tanpa reload, butang ubah urutan (`Move Up` / `Move Down` `sort_order`), serta toggle Published.
  4. **Delete:** Butang Delete pada setiap projek berserta **Confirmation Modal** keselamatan.

### 4. Database Seeder (`ProjectSeeder.php` — Data Sebenar me.zaryue.com)
- Jana rekod projek sebenar:
  1. **Personal portfolio website**:
     - `description`: `'Single-page portfolio with admin dashboard built on Laravel + Livewire + Flux UI, featuring CRUD content management, authentication with 2FA, drag-free sorting, image upload, contact form with email notification, and CI/CD via GitHub Actions.'`
     - `tech_stack`: `["Livewire", "Tailwind CSS", "CSS", "PHP", "HTML", "MySQL", "Laravel", "GitHub"]`
     - `demo_url`: `'https://me.zaryue.com/'`, `is_featured`: `true`, `is_published`: `true`, `sort_order`: `1`.