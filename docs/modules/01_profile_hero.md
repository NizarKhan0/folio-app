# Modul 01: Profile, Hero Settings & Dashboard Overview
> Status: ✅ Siap · Disegerakkan 22 November 2026
- Bergantung pada: Tiada · Menyediakan untuk: Modul 02, 03, 04 · Keperluan PRD: `FR-01`

## 1. Tujuan
Membolehkan pemilik portfolio mengurus identiti tunggal (`Profile` singleton `id = 1`), tetapan Hero (`headline`, `tagline`, `bio`, `location`, `is_available`), e-mel anti-spam (`[at]`), pautan sosial/CV, muat naik media (`avatar`, `banner`, `resumeFile`), serta memantau KPI ringkas pada `/dashboard`.

## 2. Data (Migration & Model)
- Jadual `profiles` (Tiada `user_id`):
  - `id`, `name` (string), `headline` (string), `tagline` (string), `bio` (text, nullable), `location` (string), `is_available` (boolean, default: `true`), `email` (string, nullable), `github_url` (string, nullable), `linkedin_url` (string, nullable), `resume_url` (string, nullable), `resume_path` (string, nullable), `avatar_path` (string, nullable), `banner_path` (string, nullable), `timestamps`.
- Model `Profile`:
  - Cast `is_available` sebagai `boolean`.
  - Helper statik `Profile::current(): self` (`firstOrCreate(['id' => 1], [...])`).
  - Helper media: `avatarUrl(): ?string`, `bannerUrl(): ?string`, `resolvedResumeUrl(): ?string`.

## 3. Logik (`ProfileEditor.php`)
- Validasi `email` menggunakan `['nullable', 'string', 'max:255']` (membenarkan format anti-spam seperti `nizarkhan.digital[at]gmail.com`).
- Muat naik `avatar` (max 2MB), `banner` (max 4MB), dan `resumeFile` PDF (max 5MB) melalui `WithFileUploads` dengan pembersihan fail lama (`replaceStoredFile`).
- Toggle satu-klik `toggleAvailability()` untuk `is_available` tanpa reload.

## 4. UI / API
- Route: `GET /dashboard` (`dashboard.blade.php`), `GET /admin/profile` (`ProfileEditor::class`).
- Menggunakan layout Cyberpunk HUD (`resources/views/layouts/app/sidebar.blade.php`), aksen Cyan, dan menu sidebar.

### 4a. Senarai Skrin
| Skrin | Route | Siapa | Elemen Utama | Tindakan |
| :--- | :--- | :--- | :--- | :--- |
| Overview Dashboard | `/dashboard` | Admin | 4 kad KPI Neon, status availability, ringkasan profil | Pautan pantas ke 4 modul |
| Profile & Hero Editor | `/admin/profile` | Admin | Borang Hero, toggle availability, input `[at]` e-mel, upload avatar/banner/PDF | Save, Toggle Availability, Remove Media |

### 4b. Wireframe Ringkas (`/admin/profile`)
```text
+------------------------------------------------------------------------+
| 01 // IDENTITY & HERO        [Available for Work]   [Save Changes]     |
|------------------------------------------------------------------------|
| [Avatar Upload]   [Banner Upload]   [Resume PDF Upload]                |
| Name: [Nizar Khan]            Headline: [Hi, I'm Nizar Khan]           |
| Tagline: [Fullstack PHP Developer · Laravel, Livewire & Filament]      |
| Bio: [I design and ship reliable web platforms...]                     |
| Location: [Kuala Lumpur, Malaysia]  Email: [nizarkhan.digital[at]...]  |
+------------------------------------------------------------------------+
```

## 5. Ujian & Seeder
- `ProfileEditorTest`: Paparan, kemas kini teks, toggle availability, pengesahan `email` `[at]`, muat naik/padam media, dan `ProfileSeeder`.
- `ProfileSeeder`: Rekod `id = 1` (`Nizar Khan`, `Kuala Lumpur, Malaysia`, `nizarkhan.digital[at]gmail.com`, `https://github.com/NizarKhan0`). `DatabaseSeeder` mencipta akaun lalai `test@example.com`.

## 6. Acceptance Criteria
- [x] Pemilik boleh kemas kini maklumat Hero, e-mel `[at]`, pautan sosial, dan muat naik media tanpa reload.
- [x] `ProfileEditorTest` & `ProfileSeeder` lulus semua Quality Gates.
