# Modul 04: Public Single-Page Portfolio & Contact Message Inbox
Rujuk `AGENT.md` dan `docs/ARCHITECTURE.md`. Bina **Modul 04: Public Single-Page Portfolio & Contact Message Inbox** (100% English Web UI & Full CRUD):

### 1. Migration & Schema (`messages` — Tiada `user_id`)
- `messages`: `id`, `name` (string), `email` (string), `subject` (string, nullable), `message` (text), `is_read` (boolean, default: false), `timestamps`. Indeks: `['is_read', 'created_at']`.

### 2. Public Single-Page Portfolio (`app/Livewire/PublicPortfolio.php` pada laluan `/`)
- Paparkan keseluruhan laman portfolio awam dalam satu halaman yang kemas, moden, dan responsif:
  1. **Navbar:** Logo/Nama `Nizar Khan`, pautan sauh (`Home`, `Projects`, `Experience`, `Skills`, `Contact`), dan pautan `Dashboard` jika sedang log masuk.
  2. **Hero Section:** Data daripada `Profile::current()` (`Hi, I\'m Nizar Khan`, tagline, bio, butang `Contact Me` & `View Projects`).
  3. **Projects Section:** Grid projek daripada `Project::published()` lengkap dengan lencana `tech_stack` dan pautan `Demo →`.
  4. **Experience Section:** Garis masa daripada `Experience::orderBy('sort_order')->get()` lengkap dengan huruf awal syarikat (`O`, `I`, `M`), lokasi, tempoh, huraian, dan lencana `tech_stack`.
  5. **Skills Section:** Grid kemahiran daripada `Skill::orderBy('sort_order')->get()`.
  6. **Get In Touch (Contact Form):** Borang Livewire reaktif (`name`, `email`, `subject`, `message`) yang terus menyimpan ke jadual `messages` + menghantar notifikasi e-mel/Telegram secara opsyenal menggunakan `rescue()`.

### 3. Admin Message Inbox (`app/Livewire/Admin/MessageInbox.php`)
- Gunakan layout sidebar Livewire Starter Kit dan tambah menu `Messages` (berserta badge kiraan `unread`) pada sidebar.
- **MANDATORY Operations:**
  1. **Read (Admin):** Kad kiraan Unread Messages & jadual senarai mesej masuk.
  2. **Update (Admin):** Modal baca mesej penuh & toggle satu-klik Read/Unread tanpa reload.
  3. **Delete (Admin):** Butang Delete berserta **Confirmation Modal** keselamatan.

### 4. Database Seeder (`MessageSeeder.php`)
- Jana 2 contoh mesej pertanyaan kerja/projek di dalam jadual `messages` (1 `is_read = false`, 1 `is_read = true`) dan panggil kesemua seeder di dalam `DatabaseSeeder.php`.