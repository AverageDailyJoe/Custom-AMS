# 🐧 Panduan Instalasi & Deployment AMS pada Arch Linux

Dokumen ini berisi panduan lengkap menginstal dan menjalankan **Asset Management System (AMS)** pada distribusi Linux berbasis Arch Linux (Arch, Manjaro, EndeavourOS, dll.).

---

## 🛠️ 1. Instalasi Packages & Dependensi (Pacman)

Buka terminal di Arch Linux dan install package PHP, PostgreSQL, Composer, serta dependensi yang dibutuhkan:

```bash
sudo pacman -Syu
sudo pacman -S php php-pgsql php-gd php-intl composer postgresql git nodejs npm
```

---

## ⚙️ 2. Konfigurasi PHP Extension (`php.ini`)

Buka file konfigurasi PHP `/etc/php/php.ini` menggunakan editor teks (misal `sudo nano /etc/php/php.ini`):

```bash
sudo nano /etc/php/php.ini
```

Pastikan extension berikut diaktifkan (hilangkan tanda titik koma `;` di awal baris):

```ini
extension=bcmath
extension=curl
extension=fileinfo
extension=gd
extension=intl
extension=mbstring
extension=openssl
extension=pdo_pgsql
extension=pgsql
extension=zip
```

---

## 🐘 3. Inisialisasi & Konfigurasi PostgreSQL

### A. Inisialisasi Cluster PostgreSQL (Khusus Instalasi Baru)
```bash
sudo -u postgres initdb -D /var/lib/postgres/data
```

### B. Jalankan & Enable Service PostgreSQL (Systemd)
```bash
sudo systemctl enable --now postgresql
```
*Cek status service:*
```bash
systemctl status postgresql
```

### C. Buat Database & User AMS
Masuk ke prompt PostgreSQL sebagai user `postgres`:
```bash
sudo -u postgres psql
```

Jalankan perintah SQL berikut:
```sql
CREATE DATABASE ams_dev;
CREATE USER ams_user WITH PASSWORD 'password_kamu';
GRANT ALL PRIVILEGES ON DATABASE ams_dev TO ams_user;
ALTER DATABASE ams_dev OWNER TO ams_user;
\q
```

---

## 📂 4. Setup Proyek AMS (Laravel)

### A. Clone / Extract Proyek
```bash
cd ~
git clone <URL_REPOSITORY_KAMU> ams-app
cd ams-app
```

### B. Konfigurasi File `.env`
Salin `.env.example` ke `.env` jika belum ada:
```bash
cp .env.example .env
```

Pastikan isi `.env` sudah sesuai dengan database PostgreSQL dan timezone lokal kamu:
```env
APP_NAME="Asset Management System"
APP_ENV=local
APP_KEY=base64:7+iDkd/SuEd2McbjFr+Yi0zRpKEq5FBeGlhwMl1bytY=
APP_DEBUG=true
APP_URL=http://localhost:8000
APP_TIMEZONE=Asia/Jakarta

DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=ams_dev
DB_USERNAME=ams_user
DB_PASSWORD=password_kamu
```

### C. Install Dependensi PHP (Composer)
```bash
composer install
```

### D. Run Migration Database & Asset Storage Link
```bash
php artisan migrate --force
php artisan storage:link
php artisan optimize:clear
```

### E. Buat Akun Admin Utama (Jika Database Kosong)
Jalankan command interaktif atau buat user admin:
```bash
php artisan filament:user
```
atau jalankan file generator admin:
```bash
php create_admin.php
```

---

## 🚀 5. Menjalankan Web Server

Jalankan dev server bawaan Laravel:
```bash
php artisan serve
```

Akses web browser di:
👉 **[http://localhost:8000/admin](http://localhost:8000/admin)**

---

## 📊 6. Import Data Master (CSV) pada Arch Linux

Untuk meng-import data master dari Excel/CSV di Arch Linux:
```bash
php artisan ams:import-csv "/path/ke/MasterImport.csv"
```
