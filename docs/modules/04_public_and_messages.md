# Modul 04: Public Single-Page Portfolio & Contact Message Inbox
Rujuk `AGENT.md` dan `docs/ARCHITECTURE.md`. Bina **Modul 04: Public Single-Page Portfolio & Contact Message Inbox** (100% English Web UI & Full CRUD):

### 1. Migration & Schema (`messages` — Tiada `user_id`)
- `messages`: `id`, `name` (string), `email` (string), `subject` (string, nullable), `message` (text), `is_read` (boolean, default: false), `timestamps`. Indeks: `['is_read', 'created_at']`.

### 2. Public Single-Page Portfolio (`app/Livewire/PublicPortfolio.php` pada laluan `/`)
- Paparkan keseluruhan laman portfolio awam dalam satu halaman bertema Cyberpunk HUD yang kemas, moden, dan responsif untuk mobile, tablet, & desktop:
  1. **Navbar:** Logo/Nama `Nizar Khan`, status `SYS // ONLINE`, pautan sauh (`Home`, `Projects`, `Experience`, `Skills`, `Contact`), serta butang menu hamburger + laci navigasi untuk mobile (`md:hidden`).
  2. **Hero Section:** Data daripada `Profile::current()` (`Hi, I'm Nizar Khan`, tagline, bio, butang `Contact Me`, `View Projects`, & pautan Resume/GitHub/LinkedIn) berserta kad identiti 3D Cyberpunk (`NETRUNNER_ID // 001`) dan statistik langsung.
  3. **Projects Section:** Grid 3-lajur (`lg:grid-cols-3`) memaparkan maksimum 6 projek featured daripada `Project::published()`, lencana `tech_stack`, pautan `Demo →`/`Repository →`, butang `Show Details` (modal `<flux:modal>`), serta butang `All Projects (N) →` yang membuka katalog penuh bersama native top-layer `<dialog>` (`showModal()`) untuk `Show Details` tanpa menutup modal induk.
  4. **Experience Section:** Garis masa daripada `Experience::orderBy('sort_order')->get()` lengkap dengan huruf awal syarikat (`O`, `I`, `M`), lokasi, tempoh, huraian boleh kembang (`Details +` / `Hide −`), dan lencana `tech_stack`.
  5. **Skills Section:** Matriks keupayaan mengikut kategori (`backend`, `frontend`, `database`, `devops_tools`) dengan penapis kategori reaktif (`All`, `Backend`, dll.).
  6. **Get In Touch (Contact Form):** Borang Livewire reaktif (`name`, `email`, `subject`, `message`) yang terus menyimpan ke jadual `messages` + menghantar notifikasi e-mel secara opsyenal menggunakan `rescue()`.

### 3. Admin Message Inbox (`app/Livewire/Admin/MessageInbox.php`)
- Gunakan layout sidebar Livewire Starter Kit dan tambah menu `Messages` (berserta badge kiraan `unread`) pada sidebar.
- **MANDATORY Operations:**
  1. **Read (Admin):** Kad kiraan Unread Messages & jadual senarai mesej masuk.
  2. **Update (Admin):** Modal baca mesej penuh & toggle satu-klik Read/Unread tanpa reload.
  3. **Delete (Admin):** Butang Delete berserta **Confirmation Modal** keselamatan.

### 4. Database Seeder (`MessageSeeder.php`)
- Jana 2 contoh mesej pertanyaan kerja/projek di dalam jadual `messages` (1 `is_read = false`, 1 `is_read = true`) dan panggil kesemua seeder di dalam `DatabaseSeeder.php`.