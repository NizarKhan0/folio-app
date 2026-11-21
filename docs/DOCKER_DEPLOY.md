# 🐳 Panduan Docker & Home Server Deployment (`folio-app`)
> Disegerakkan 22 November 2026 · Port Lalai: `9002` · Domain Sasaran: `https://nizarkhan.dev`

## 1. Ringkasan & Penerangan Lengkap Fail Docker
| Fail | Fungsi Utama |
| :--- | :--- |
| `Dockerfile` | Membina imej produksi aplikasi (`node:22-alpine` + `php:8.4-apache`) |
| `docker-compose.yml` | Mengorkestra kontena `folio_app` (port `9002`) & `folio_db` (MySQL 8.0) serta volume kekal |
| `docker/entrypoint.sh` | Skrip permulaan kontena (media awal, `storage:link`, tunggu MySQL, `migrate`, `optimize`, Apache) |
| `.dockerignore` | Menapis fail tempatan supaya build imej Docker kekal pantas, kecil, dan selamat |
| `database/seeders/media/` | Salinan imej avatar & thumbnail projek yang dijejak dalam Git untuk dibawa ke Docker |

### 1.1 `Dockerfile` (Multi-Stage Build)
1. **Stage 1 (`assets` — `node:22-alpine`):**
   - Memasang kebergantungan npm (`npm ci`) dan menjalankan `npm run build` untuk mengkompilasi Vite + Tailwind CSS v4 + font ke `public/build/`.
   - Imej Node tidak dibawa masuk ke imej akhir supaya saiz kontena produksi kekal ringan.
2. **Stage 2 (`php:8.4-apache`):**
   - Memasang sambungan PHP yang diperlukan oleh Laravel 13, Livewire 4, dan muat naik imej (`pdo_mysql`, `mbstring`, `exif`, `pcntl`, `bcmath`, `gd` dengan sokongan JPEG/PNG/WebP/FreeType, `zip`, `intl`, `opcache`).
   - Mengaktifkan modul Apache `rewrite` & `headers` serta menukar `APACHE_DOCUMENT_ROOT` kepada `/var/www/html/public`.
   - Memasang kebergantungan PHP produksi (`composer install --no-dev --optimize-autoloader`), menyalin hasil `public/build` dari Stage 1, menjalankan `php artisan package:discover`, membuang `public/hot`, dan menetapkan `ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]`.

### 1.2 `docker-compose.yml` (Servis, Port & Volume Kekal)
1. **Servis `app` (`folio_app`):**
   - Membina imej daripada `Dockerfile` dan berjalan dengan `restart: unless-stopped` (hidup semula automatik jika mini PC reboot).
   - Memetakan port hos **`9002`** (`${APP_PORT:-9002}:80`) supaya tidak bertembung dengan servis lain di home server.
   - Membaca `.env` (`env_file`) serta memaksa `DB_CONNECTION=mysql`, `DB_HOST=db`, `DB_PORT=3306` untuk berhubung dengan kontena `db`.
   - Memasang volume kekal `storage_public:/var/www/html/storage/app/public` supaya avatar dan thumbnail tidak hilang apabila kontena dibina semula.
   - Menunggu servis `db` berstatus `healthy` sebelum bermula (`depends_on: condition: service_healthy`).
2. **Servis `db` (`folio_db` — `mysql:8.0`):**
   - Menyimpan pangkalan data dalam volume kekal `mysql_data:/var/lib/mysql`.
   - Mempunyai `healthcheck` (`mysqladmin ping`) setiap 5 saat untuk memastikan MySQL benar-benar bersedia menerima sambungan.

### 1.3 `docker/entrypoint.sh` (Skrip Permulaan Kontena)
Dijalankan secara automatik setiap kali kontena `folio_app` mula hidup (kerana semasa `docker build`, MySQL belum hidup dan volume `storage_public` belum dipasang):
1. Mencipta direktori `storage/*` & `bootstrap/cache` jika belum wujud.
2. Menyalin gambar avatar & thumbnail daripada `database/seeders/media/.` ke `storage/app/public/` menggunakan `cp -rn` (hanya salin fail yang belum wujud; tidak menimpa muat naik baharu anda).
3. Menetapkan pemilik fail kepada `www-data:www-data` supaya Apache/PHP boleh menulis log, cache, dan muat naik fail.
4. Mencipta pautan simbolik `public/storage` (`php artisan storage:link`) jika belum wujud.
5. Menunggu sambungan PDO MySQL (`db:3306`) sedia setiap 2 saat.
6. Menjalankan `php artisan migrate --force` dan `php artisan optimize` (cache config, route, & view).
7. Memulakan pelayan web `apache2-foreground` sebagai proses utama kontena.

### 1.4 `.dockerignore` (Penapis Konteks Build)
Mengecualikan fail yang tidak patut masuk ke dalam imej Docker:
- `.env` & `.env.*` (rahsia & tetapan dibekalkan terus di server melalui `env_file`), `.git`, `.github`.
- `node_modules`, `vendor`, `public/build`, `public/hot`, `public/storage` (dibina bersih di dalam `Dockerfile` / `entrypoint.sh`).
- `storage/logs/*.log`, cache rangka kerja, `tests`, dan `.phpunit.result.cache`.

---

## 2. Langkah Pemasangan di Home Server (Ubuntu Mini PC)

### Langkah 1 — Pasang Docker & Docker Compose
```bash
sudo apt update && sudo apt install -y docker.io docker-compose-plugin
sudo usermod -aG docker $USER
```
*(Log keluar dan masuk semula SSH selepas `usermod` supaya arahan `docker` boleh dijalankan tanpa `sudo`.)*

### Langkah 2 — Sediakan Projek & Fail `.env`
```bash
cd /opt/folio-app
cp .env.example .env
nano .env
```
Tetapan minimum yang disyorkan dalam `.env` server:
```env
APP_NAME="Nizar Khan"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://nizarkhan.dev
APP_PORT=9002

DB_CONNECTION=mysql
DB_HOST=db
DB_PORT=3306
DB_DATABASE=folio_app
DB_USERNAME=folio
DB_PASSWORD=tukar_password_kuat
DB_ROOT_PASSWORD=tukar_root_password_kuat

MAIL_MAILER=smtp
MAIL_SCHEME=smtps
MAIL_HOST=smtp.resend.com
MAIL_PORT=465
MAIL_USERNAME=resend
MAIL_PASSWORD=re_api_key_anda
MAIL_FROM_ADDRESS="noreply@nizarkhan.dev"
MAIL_FROM_NAME="${APP_NAME}"
```

### Langkah 3 — Bina & Jalankan Kontena
```bash
docker compose up -d --build
```

### Langkah 4 — Jana Kunci Aplikasi & Masukkan Data Awal
Kerana `.dockerignore` mengecualikan `.env` daripada imej Docker, jana `APP_KEY` menggunakan `--show` dan simpan ke `/opt/folio-app/.env` di hos, kemudian recreate kontena:
```bash
NEW_KEY=$(docker compose exec -T app php artisan key:generate --show | tr -d '\r\n')
sed -i "s|^APP_KEY=.*|APP_KEY=${NEW_KEY}|" .env
docker compose up -d
docker compose exec app php artisan db:seed --force
```
- `docker/entrypoint.sh` akan menyalin kesemua imej avatar & thumbnail dari `database/seeders/media/` ke volume `storage_public` secara automatik pada permulaan pertama (tanpa menimpa imej baharu yang dimuat naik melalui Admin nanti).
- Selepas selesai, laman boleh dicapai di `http://IP-MINI-PC:9002`, dan log masuk admin di `/sys-console/login`.

---

## 3. Sambungan Domain & HTTPS (Cloudflare Tunnel / Reverse Proxy)
1. Halakan trafik domain `https://nizarkhan.dev` ke **`http://localhost:9002`** menggunakan **Cloudflare Tunnel (`cloudflared`)** atau **Nginx Proxy Manager**.
2. Uji laluan SEO selepas domain aktif:
   - `https://nizarkhan.dev/sitemap.xml`
   - `https://nizarkhan.dev/robots.txt` (automatik memaparkan `Sitemap: https://nizarkhan.dev/sitemap.xml`)

---

## 4. Arahan Penyelenggaraan Harian

- **Kemaskini kod baharu dari Git:**
  ```bash
  git pull
  docker compose up -d --build
  ```
- **Semak log aplikasi:**
  ```bash
  docker compose logs -f app
  ```
- **Backup pangkalan data MySQL:**
  ```bash
  docker compose exec db mysqldump -u root -p"$DB_ROOT_PASSWORD" folio_app > backup_$(date +%F).sql
  ```
- **Hentikan kontena (data kekal selamat dalam volume):**
  ```bash
  docker compose down
  ```
