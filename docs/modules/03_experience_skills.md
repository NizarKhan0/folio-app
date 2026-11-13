# Modul 03: Experience & Skills Manager
Rujuk `AGENT.md` dan `docs/ARCHITECTURE.md`. Bina **Modul 03: Experience & Skills Manager** (100% English Web UI & Full CRUD):

### 1. Migrations & Schema (`experiences` & `skills` — Tiada `user_id`)
- `experiences`: `id`, `role` (string), `company` (string), `location` (string, nullable), `period` (string, cth: 'February 2025 - Present'), `description` (text), `tech_stack` (json, nullable), `sort_order` (integer, default: 0), `timestamps`. Indeks: `['sort_order']`.
- `skills`: `id`, `name` (string, unique), `category` (enum: 'backend', 'frontend', 'database', 'devops_tools', default: 'backend'), `sort_order` (integer, default: 0), `timestamps`. Indeks: `['category', 'sort_order']`.

### 2. Models & Business Logic (`Experience.php`, `Skill.php`)
- Cast `tech_stack` pada `Experience` sebagai `array`.
- Sediakan accessor `companyInitial(): string` pada `Experience` (cth: `'O'` untuk Opensoft, `'I'` untuk Intesh, `'M'` untuk MRIPET seperti di `me.zaryue.com`).

### 3. Livewire UI (`app/Livewire/Admin/ExperienceSkillManager.php`)
- Gunakan layout sidebar Livewire Starter Kit dan tambah menu `Experience & Skills` pada sidebar.
- **UI Language:** 100% Professional English ('Experience', 'Skills', 'Add Experience', 'Add Skill', 'Delete').
- **MANDATORY Full CRUD (2 Tab Reaktif: Experiences & Skills):**
  1. **Create:** Modal tambah Experience baharu (lengkap input tag `tech_stack`) & Modal tambah Skill baharu.
  2. **Read:** Senarai Experience lengkap dengan lencana huruf awal syarikat (`O`, `I`, `M`), tag teknologi, serta grid lencana Skills.
  3. **Update:** Butang Edit pada setiap Experience dan Skill tanpa reload, serta kawalan urutan `sort_order`.
  4. **Delete:** Butang Delete pada setiap rekod berserta **Confirmation Modal** keselamatan.

### 4. Database Seeder (`ExperienceSkillSeeder.php` — 100% Data Sebenar me.zaryue.com)
- **A. 3 Pengalaman Kerja Sebenar (`experiences`):**
  1. **IT Developer** | `Opensoft Technologies Sdn Bhd` | `Putrajaya, Malaysia` | `February 2025 - Present`:
     - `description`: `'I successfully migrated digital assets from legacy platforms with 100% data accuracy and integrity, while enhancing website sections like galleries and news to align with stakeholder needs. I performed precise data entry following strict formatting and validation protocols, designed responsive layouts using HTML, CSS, Bootstrap, and JavaScript, and developed backend functionality with Yii2 to improve system scalability. Additionally, I built and maintained a custom Laravel-based CMS for non-technical teams, handled secure code patching and updates on production servers via VPN and onsite deployment, and actively participated in UAT sessions to translate feedback into technical requirements, ensuring seamless project handovers and compliance with new system standards.'`
     - `tech_stack`: `["Livewire", "Bootstrap", "Tailwind CSS", "Alpine JS", "GitHub", "Filament", "Laravel", "MySQL", "JQuery", "JavaScript", "CSS", "HTML", "PHP", "Yii2", "DevOps"]`
  2. **Junior Web Programmer** | `Intesh Synergy Sdn Bhd` | `Cyberjaya, Selangor` | `December 2023 – January 2025`:
     - `description`: `'Maintained company website using Laravel, corrected 70% of inaccurate dashboard data, improved system performance by 5% through optimized MySQL queries, and reduced manual data entry by 20% with an export-to-Excel feature.'`
     - `tech_stack`: `["Laravel", "PHP", "HTML", "CSS", "JavaScript", "JQuery", "MySQL", "JIRA", "Bootstrap", "Tailwind CSS"]`
  3. **Web Developer (Internship)** | `Malaysia Research Institute For Professional Education And Training` | `Parit Raja, Johor` | `September 2022 – February 2023`:
     - `description`: `'Developed a researcher registration website using PHP/MySQL, performed system analysis (ERD, DFD, relational schema), and created multimedia content including corporate videos, certificate templates, and book covers.'`
     - `tech_stack`: `["HTML", "CSS", "JavaScript", "JQuery", "MySQL", "Bootstrap", "PHP"]`
- **B. 18 Skills Sebenar (`skills`):**
  - `PHP`, `Laravel`, `Livewire`, `Filament`, `Python`, `MySQL`, `JavaScript`, `JQuery`, `Alpine JS`, `Tailwind CSS`, `Bootstrap`, `HTML`, `CSS`, `Docker`, `GitHub`, `JIRA`, `Postman`, `Test`.