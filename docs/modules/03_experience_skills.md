# Modul 03: Experience & Skills Manager
> Status: ✅ Siap · Disegerakkan 22 November 2026
- Bergantung pada: Modul 01 · Menyediakan untuk: Modul 04 · Keperluan PRD: `FR-03`

## 1. Tujuan
Mengurus rekod pengalaman kerja (`Experience`) lengkap dengan tempoh, lokasi, dan `tech_stack`, serta katalog kemahiran teknikal (`Skill`) mengikut 4 kategori (`backend`, `frontend`, `database`, `devops_tools`).

## 2. Data (Migration & Model)
- Jadual `experiences` (Tiada `user_id`):
  - `id`, `role` (string), `company` (string), `location` (string, nullable), `period` (string), `description` (text), `tech_stack` (json, nullable), `sort_order` (integer, default: `0`), `timestamps`. Indeks: `['sort_order']`.
- Jadual `skills` (Tiada `user_id`):
  - `id`, `name` (string, unik), `category` (enum: `backend`, `frontend`, `database`, `devops_tools`), `sort_order` (integer, default: `0`), `timestamps`. Indeks: `['category', 'sort_order']`.
- Model `Experience`: cast `tech_stack` sebagai `array`, accessor `companyInitial(): string` (`O`, `I`, `M`).

## 3. Logik (`ExperienceSkillManager.php`)
- Dua tab reaktif (`experiences` & `skills`) dalam satu komponen Livewire dengan Full CRUD, `sort_order`, dan Confirmation Modal pemadaman.

## 4. UI / API (Full CRUD)
- Route: `GET /admin/experience-skills` (`ExperienceSkillManager::class`).
- Header: `03 // Career & Stack` dengan aksen Cyan Cyberpunk.

### 4a. Senarai Skrin
| Skrin / Modal | Route | Siapa | Elemen Utama | Tindakan |
| :--- | :--- | :--- | :--- | :--- |
| Experience & Skills | `/admin/experience-skills` | Admin | Tab Experiences & Tab Skills, senarai lencana syarikat & kategori | Switch Tab, Add, Edit, Delete |
| Modal Experience / Skill | — (Modal) | Admin | Borang role/company/period/tech_stack atau skill name/category | Save, Cancel |
| Modal Confirm Delete | — (Modal) | Admin | Amaran pengesahan pemadaman rekod | Confirm Delete, Cancel |

### 4b. Wireframe Ringkas (`/admin/experience-skills`)
```text
+------------------------------------------------------------------------+
| 03 // CAREER & STACK          [ Experiences (3) ] [ Skills (27) ]      |
|------------------------------------------------------------------------|
| [O] IT Developer — Opensoft Technologies Sdn Bhd   [Edit] [Delete]     |
| [I] Junior Web Programmer — Intesh Synergy         [Edit] [Delete]     |
| [M] Web Developer (Internship) — MRIPET            [Edit] [Delete]     |
+------------------------------------------------------------------------+
```

## 5. Ujian & Seeder
- `ExperienceSkillManagerTest`: Full CRUD Experiences & Skills serta `ExperienceSkillSeeder` (3 pengalaman & 27 skills).
- `ExperienceSkillSeeder` (Data Terkini):
  - **3 Pengalaman Kerja:**
    1. `IT Developer` | `Opensoft Technologies Sdn Bhd` (`Putrajaya, Malaysia`, `February 2025 - Present`, termasuk `Yii2`, `DevOps`, `Go`)
    2. `Junior Web Programmer` | `Intesh Synergy Sdn Bhd` (`Cyberjaya, Selangor`, `December 2023 – January 2025`)
    3. `Web Developer (Internship)` | `Malaysia Research Institute For Professional Education And Training` (`Parit Raja, Johor`, `September 2022 – February 2023`)
  - **27 Skills (4 Kategori):**
    - `backend` (7): `PHP`, `Laravel`, `Livewire`, `Filament`, `Python`, `Go`, `Yii2`
    - `database` (2): `MySQL`, `SQLite`
    - `frontend` (8): `JavaScript`, `jQuery`, `Alpine.js`, `Tailwind CSS`, `Bootstrap`, `HTML`, `CSS`, `Flux UI`
    - `devops_tools` (10): `Docker`, `GitHub`, `JIRA`, `Postman`, `Git`, `GitHub Actions`, `Linux / Ubuntu`, `Claude`, `OpenCode`, `Antigravity`

## 6. Acceptance Criteria
- [x] Pemilik boleh melakukan Full CRUD pada Experiences dan Skills merentasi 4 kategori.
- [x] `ExperienceSkillManagerTest` mengesahkan 3 experiences dan 27 skills lulus semua Quality Gates.
