# 🧭 DevOps & Production Operations Handbook (`folio-app`)
> Nota praktikal & rujukan standard industri daripada perbincangan setup Home Server, Docker, Domain `.dev`, E-mel & SEO.

---

## 1. Struktur Direktori Linux (`/opt` vs `/srv` vs `/home`)
- **`/opt/<nama-app>`** *(Pilihan Standard Industri untuk Docker Compose)*: Menyimpan aplikasi lengkap yang berdiri sendiri (kod, `Dockerfile`, `docker-compose.yml`, `.env`). Contoh: `/opt/folio-app`.
- **`/srv/<nama-app>`**: Untuk data/kandungan yang dilayan oleh servis pelayan (web statik, FTP, Git).
- **`/home/<user>`**: Untuk fail peribadi pengguna; berfungsi di home server solo, tetapi `/opt/` lebih standard untuk produksi.
- **`/opt/containerd`**: Folder dalaman enjin Docker — **jangan sentuh atau ubah**.

### Maksud Flag Asas Linux:
- `sudo mkdir -p /opt/folio-app` → `-p` (*parents*): cipta folder induk sekali gus jika belum ada dan **tidak ralat** jika folder sudah wujud.
- `sudo chown -R $USER:$USER /opt/folio-app` → `-R` (*recursive*): tukar pemilik folder tersebut **serta semua subfolder dan fail di dalamnya**.
- **Nota penting:** Jangan padam folder `/opt/folio-app` selepas `docker compose up -d --build` kerana `docker-compose.yml`, `.env`, dan `git pull` bergantung pada folder ini.

---

## 2. Pengasingan Kontena, `.env` & `php artisan`

### Kenapa `php artisan` Tidak Boleh Dijalankan Terus di Host?
- Pakej PHP (`vendor/`) hanya dipasang **di dalam kontena Docker `folio_app`**, bukan pada hos Ubuntu (`/opt/folio-app/vendor` tidak wujud).
- Semua arahan Artisan di server WAJIB didahului dengan `docker compose exec app`:
  ```bash
  docker compose exec app php artisan db:seed --force
  docker compose exec app php artisan migrate --force
  docker compose exec app php artisan optimize
  docker compose exec app php artisan tinker
  ```

### Kenapa `.env` Tidak Disalin Masuk Ke Dalam Imej Docker?
- `.dockerignore` mengecualikan `.env` supaya kata laluan DB & kunci API tidak dibakar ke dalam imej Docker.
- `docker-compose.yml` menghantar nilai dari `/opt/folio-app/.env` melalui `env_file`.
- Untuk jana `APP_KEY`, guna `--show` dan simpan terus ke `.env` di hos:
  ```bash
  NEW_KEY=$(docker compose exec -T app php artisan key:generate --show | tr -d '\r\n')
  sed -i "s|^APP_KEY=.*|APP_KEY=${NEW_KEY}|" .env
  docker compose up -d
  ```

---

## 3. Kenapa & Bila Guna `--force` Pada Artisan
Apabila `APP_ENV=production`, Laravel menyekat arahan yang mengubah pangkalan data atau menulis fail kritikal dengan soalan pengesahan interaktif. `--force` melangkau soalan tersebut dalam skrip/CI/CD:

| Arahan | Kegunaan `--force` |
| :--- | :--- |
| `php artisan migrate --force` | Jalankan migration pada DB produksi tanpa soalan interaktif |
| `php artisan db:seed --force` | Jalankan seeder pada DB produksi tanpa soalan interaktif |
| `php artisan key:generate --force` | Tulis semula `APP_KEY` walaupun sudah ada nilai |
| `php artisan storage:link --force` | Bina semula *symlink* `public/storage` jika rosak/sedia ada |
| `php artisan vendor:publish --tag=... --force` | Timpa fail aset/view vendor yang sedia ada |

---

## 4. Pangkalan Data MySQL Docker (`folio_db`)
- Kontena `mysql:8.0` mencipta `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`, dan `DB_ROOT_PASSWORD` **secara automatik pada kali pertama volume `mysql_data` masih kosong**.
- Isi `.env` dengan kata laluan yang betul **sebelum** menjalankan `docker compose up -d --build` kali pertama. (Elakkan aksara seperti `$` atau `#` dalam password `.env`).

---

## 5. Domain `.dev`, Cloudflare Tunnel, SSL & Pindah Ke VPS
- **Domain `.dev` & SEO:** Google melayan `.dev` sebagai *gTLD* (sama taraf dengan `.com`), sesuai untuk carian global & Malaysia (diperkukuh dengan lokasi `Kuala Lumpur, Malaysia` dalam JSON-LD dan `<title>`). Semua domain `.dev` wajib HTTPS (HSTS).
- **Cloudflare Registrar:** Harga *at-cost* tanpa *markup* renew tahunan, WHOIS privacy percuma, SSL/HTTPS percuma (*auto-renew*), dan terus siap untuk **Cloudflare Tunnel** (halakan `nizarkhan.dev` → `http://localhost:9002`, menyembunyikan IP rumah anda).
- **Pindah ke VPS pada masa depan:** Domain kekal di Cloudflare — cuma tukar rekod DNS di Cloudflare kepada `A` record → IP awam VPS baharu (atau pasang `cloudflared` di VPS) dengan **sifar kehilangan SEO**.
- **Reverse Proxy & `trustProxies` (`bootstrap/app.php`):**
  - Pelawat mengakses laman melalui **`https://`**, manakala Cloudflare Tunnel / Nginx menghantar trafik ke kontena Docker Apache (port `9002`) melalui **`http://`**.
  - Tanpa `$middleware->trustProxies(at: '*');` dalam `bootstrap/app.php`, Laravel akan menjana skrip Livewire dengan `http://` lalu disekat oleh pelayar (*Mixed Content*), menyebabkan semua butang `wire:click` (Add, Edit, Delete) tidak bertindak balas.
  - Dengan `trustProxies(at: '*')` sudah aktif, ia terus serasi di Home Server (Cloudflare Tunnel) mahupun di VPS (Nginx/Caddy). Di VPS nanti, tutup port `9002` daripada akses awam terus melalui firewall (`ufw`) supaya trafik masuk hanya melalui reverse proxy HTTPS.

---

## 6. E-mel Produksi (Resend) & Pemantauan SEO
- **Resend SMTP (`resend.com`):** Percuma 3,000 e-mel/bulan; sahkan domain `nizarkhan.dev` melalui Cloudflare, cipta API Key `re_...`, dan tetapkan dalam `.env`:
  ```env
  MAIL_MAILER=smtp
  MAIL_HOST=smtp.resend.com
  MAIL_PORT=465
  MAIL_SCHEME=smtps
  MAIL_USERNAME=resend
  MAIL_PASSWORD=re_api_key_anda
  MAIL_FROM_ADDRESS="noreply@nizarkhan.dev"
  MAIL_FROM_NAME="Nizar Khan Portfolio"
  ```
- **Google Search Console:** Daftar `https://nizarkhan.dev` dan hantar **`https://nizarkhan.dev/sitemap.xml`** (`sitemap.xml` & `robots.txt` dijana secara dinamik oleh `routes/web.php`).
