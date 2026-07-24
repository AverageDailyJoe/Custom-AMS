# 🖥️ Custom Asset Management System (AMS)

Asset Management System (AMS) berbasis **Laravel 11**, **Filament v3**, dan **PostgreSQL** yang dirancang khusus untuk manajemen aset TI & inventaris perusahaan dengan skalabilitas tinggi, pencatatan riwayat checkout/checkin yang presisi, serta pengarsipan dokumen fisik secara digital.

---

## ✨ Fitur-Fitur Utama (Key Features)

- ⚡ **Dashboard Management Interaktif**: Antarmuka berbasis Filament v3 modern, responsif, dan mendukung Dark Mode.
- 📦 **Master Data Asset Lengkap**: Pencatatan ID Inventaris (`Asset Tag`), Serial Number, Lokasi Utama, Ruangan / Detail Lokasi, Departemen, Model Unit, Spesifikasi Hardware (Processor, RAM, HDD, SSD, VGA), ID Monitor, Spesifikasi Monitor, Harga, Tahun Pembelian, Garansi, dan Kondisi.
- 👥 **Dual User Handover (Pengguna Utama & Pendamping)**: Mendukung penanggung jawab utama (`Primary User`) dan pendamping (`Secondary User`).
- 🔄 **Sistem Checkout & Checkin Otomatis**: Alur penyerahan dan pengembalian unit secara real-time yang langsung mengubah status aset (`in_stock` / `checked_out`) serta mencatat riwayat transaksi.
- 📎 **Multi-Attachment & Handover Proof**: Upload banyak bukti dokumentasi serah terima / pengembalian unit (foto JPG, PNG, WEBP, dan PDF) dengan modal preview interaktif.
- 📄 **Arsip Dokumen Fisik Asset**: Fitur pengarsipan dokumen fisik (Invoice, Manual Book, Kartu Garansi, Nota Pembelian) langsung pada setiap aset untuk mencegah kehilangan arsip fisik.
- 📊 **CLI Master Data Importer (`ams:import-csv`)**: Importer data dari file Excel/CSV otomatis yang mendukung 24 header kolom master data serta deteksi otomatis pemisah CSV (Comma, Semicolon, Tab).
- 🕒 **Sinkronisasi Waktu Server**: Konfigurasi otomatis timezone server lokal (`Asia/Jakarta` - WIB / UTC+7).

---

## ⚙️ Kebutuhan Dependensi Berdasarkan Operating System (OS Dependencies)

### 🪟 1. Windows (PowerShell)

**Dependensi yang Dibutuhkan:**
- PHP 8.2 / 8.3 dengan extension: `pdo_pgsql`, `pgsql`, `gd`, `intl`, `mbstring`, `fileinfo`, `openssl`, `zip`.
- PostgreSQL Server 14+ (Service: `postgresql-x64-18`).
- Composer
- Git

**Perintah Instalasi via Winget (PowerShell Admin):**
```powershell
winget install --id PHP.PHP.8.3 -e
winget install --id PostgreSQL.PostgreSQL -e
winget install --id Composer.Composer -e
winget install --id Git.Git -e
```

---

### 🐧 2. Ubuntu / Debian (Desktop & Server Build)

**Dependensi yang Dibutuhkan:**
- PHP 8.2 / 8.3 & extensions: `php8.3-cli`, `php8.3-pgsql`, `php8.3-gd`, `php8.3-intl`, `php8.3-mbstring`, `php8.3-xml`, `php8.3-curl`, `php8.3-zip`.
- PostgreSQL Server & `postgresql-contrib`.
- Composer
- Git, Node.js, & npm.

**Perintah Instalasi (Terminal APT):**
```bash
sudo apt update && sudo apt upgrade -y
sudo apt install -y php8.3-cli php8.3-pgsql php8.3-gd php8.3-intl php8.3-mbstring php8.3-xml php8.3-curl php8.3-zip postgresql postgresql-contrib composer git nodejs npm
```

---

### 🏹 3. Arch Linux (Arch, Manjaro, EndeavourOS)

**Dependensi yang Dibutuhkan:**
- Packages: `php`, `php-pgsql`, `php-gd`, `php-intl`, `postgresql`, `composer`, `git`, `nodejs`, `npm`.

**Perintah Instalasi (Terminal Pacman):**
```bash
sudo pacman -Syu
sudo pacman -S php php-pgsql php-gd php-intl composer postgresql git nodejs npm
```

*Aktifkan extensions di `/etc/php/php.ini`:*
```ini
extension=pdo_pgsql
extension=pgsql
extension=gd
extension=intl
extension=mbstring
extension=fileinfo
extension=openssl
extension=zip
```

---

### 🦎 4. openSUSE (Leap & Tumbleweed)

**Dependensi yang Dibutuhkan:**
- Packages: `php8`, `php8-pgsql`, `php8-gd`, `php8-intl`, `php8-mbstring`, `php8-zip`, `postgresql-server`, `composer`, `git`, `nodejs`.

**Perintah Instalasi (Terminal Zypper):**
```bash
sudo zypper refresh
sudo zypper install -y php8 php8-pgsql php8-gd php8-intl php8-mbstring php8-zip postgresql-server composer git nodejs npm
```

---

### 🍏 5. macOS (Homebrew)

**Dependensi yang Dibutuhkan:**
- `php`
- `postgresql@16`
- `composer`
- `node` & `git`

**Perintah Instalasi (Terminal Homebrew):**
```bash
brew update
brew install php postgresql@16 composer git node
brew services start postgresql@16
```

---

## 🚀 Panduan Instalasi & Jalankan Server (Quickstart)

1. **Clone Repository:**
   ```bash
   git clone https://github.com/AverageDailyJoe/Custom-AMS.git ams-app
   cd ams-app
   ```

2. **Setup File `.env`:**
   ```bash
   cp .env.example .env
   ```

3. **Install Dependensi Composer:**
   ```bash
   composer install
   ```

4. **Jalankan Migration & Storage Link:**
   ```bash
   php artisan migrate --force
   php artisan storage:link
   php artisan optimize:clear
   ```

5. **Jalankan Server:**
   ```bash
   php artisan serve
   ```
   Akses Dashboard Admin di: **[http://localhost:8000/admin](http://localhost:8000/admin)**

---

## 🔐 Kredensial Login Default Admin

- **URL Login**: `http://localhost:8000/admin/login`
- **Email**: `admin@ams.test`
- **Password**: `password`

---

## 📊 Import Data Master dari CSV / Excel

Untuk meng-import file CSV master data ke PostgreSQL:
```bash
php artisan ams:import-csv "path/ke/master_asset.csv"
```
Panduan teknis lengkap dapat dibaca pada file **[HowTo.MD](HowTo.MD)** dan **[Instalation.MD](Instalation.MD)**.

---

## 📄 License
The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
