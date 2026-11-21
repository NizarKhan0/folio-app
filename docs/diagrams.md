# 📐 Rajah Sistem — Nizar Khan Portfolio (`folio-app`)

## 1. ERD (Mermaid)
```mermaid
erDiagram
    USERS {
        bigint id PK
        string name
        string email UK
        timestamp two_factor_confirmed_at
    }
    PROFILES {
        bigint id PK
        string name
        string headline
        string tagline
        string location
        boolean is_available
        string email
    }
    PROJECTS {
        bigint id PK
        string title
        string slug UK
        json tech_stack
        boolean is_featured
        boolean is_published
        int sort_order
    }
    EXPERIENCES {
        bigint id PK
        string role
        string company
        string period
        json tech_stack
        int sort_order
    }
    SKILLS {
        bigint id PK
        string name UK
        enum category
        int sort_order
    }
    MESSAGES {
        bigint id PK
        string name
        string email
        string subject
        boolean is_read
    }
```

## 2. Aliran Borang Contact & Status Mesej (`messages.is_read`)
```text
[Pelawat Hantar Borang] --> [Simpan DB: is_read = false] --> [Baca di /admin/messages] --> [is_read = true]
                                    |
                                    +--> [Tukar [at] -> @ & Hantar E-mel Notifikasi (rescue)]
```

## 3. DFD Aras 0 & 4. Sitemap Matriks Akses
```text
[Pelawat Awam] --(Layari Portfolio / Hantar Mesej)--> ( 0. FOLIO-APP ) --(Notifikasi E-mel)--> [Pemilik]
[Admin]        --(Urus Profil/Projek/Skill/Inbox)--> ( 0. FOLIO-APP ) <--(Auth Fortify / 2FA)-- [Admin]
```

| Laman | Route | Pelawat Awam | Admin (`auth`, `verified`) |
| :--- | :--- | :---: | :---: |
| Portfolio Awam | `/` | ✓ Benar | ✓ Benar |
| Sitemap & Robots | `/sitemap.xml`, `/robots.txt` | ✓ Benar | ✓ Benar |
| Log Masuk Admin | `/sys-console/login` | ✓ Benar | Redirect `/dashboard` |
| Pendaftaran | `/register` | 404 (Dimatikan) | 404 (Dimatikan) |
| Dashboard & Modul Admin | `/dashboard`, `/admin/*` | 302 Login | ✓ Penuh |
| Tetapan Akaun & 2FA | `/settings/*` | 302 Login | ✓ Penuh |
