# 📖 Panduan Penggunaan & Operasional Asset Management System (AMS)

Dokumen ini berisi panduan teknis untuk menjalankan aplikasi, kredensial login admin, serta langkah-langkah import data master dari Excel / CSV ke PostgreSQL.

---

## 🚀 1. Cara Menjalankan Aplikasi (Start Server)

### Langkah A: Pastikan Service PostgreSQL Berjalan
Aplikasi menggunakan database PostgreSQL (`ams_dev`). Pastikan service PostgreSQL sudah berjalan di Windows:
```powershell
Get-Service -Name "postgresql*"
```
*Jika service stopped, jalankan via Command Prompt/PowerShell (As Admin):*
```powershell
Start-Service postgresql-x64-18
```

### Langkah B: Jalankan Web Server Laravel
Buka terminal PowerShell pada direktori aplikasi (`C:\Users\GTK-ADMIN\Downloads\ams-app`) dan jalankan:
```powershell
php artisan serve
```
Aplikasi web akan aktif dan dapat diakses melalui browser di:
👉 **[http://127.0.0.1:8000/admin](http://127.0.0.1:8000/admin)** atau **[http://localhost:8000/admin](http://localhost:8000/admin)**

---

## 🔐 2. Kredensial Akun Admin

Gunakan akun administrator berikut untuk login ke Dashboard Admin Filament:

- **URL Login**: `http://localhost:8000/admin/login`
- **Email**: `admin@ams.test`
- **Password**: `password`

---

## 📊 3. Cara Import Data Master (Sheet Excel / CSV)

Aplikasi dilengkapi dengan CLI Importer otomatis yang dapat membaca file CSV dan secara otomatis membuat Master Data (Lokasi, Kategori, Model Unit, Pengguna, Spesifikasi Hardware, dan History Checkout).

### Langkah 1: Export Sheet Excel ke CSV (UTF-8)
1. Buka file Excel Master Asset kamu.
2. Pilih sheet yang ingin di-import.
3. Klik **File** -> **Save As** / **Simpan Sebagai**.
4. Pilih format file: **CSV UTF-8 (Comma delimited) (*.csv)**.
5. Simpan file (contoh: `master_assets.csv`).

### Langkah 2: Jalankan Perintah Import
Buka terminal pada direktori aplikasi (`C:\Users\GTK-ADMIN\Downloads\ams-app`) dan jalankan perintah berikut:

```powershell
php artisan ams:import-csv "path\ke\file_kamu.csv"
```

*Contoh jika file berada di folder Downloads:*
```powershell
php artisan ams:import-csv "C:\Users\GTK-ADMIN\Downloads\master_assets.csv"
```

### Format Header Kolom (CSV / TSV / Tab-Separated) yang Didukung:
Importer secara otomatis mendeteksi pemisah koma `,`, titik koma `;`, maupun **Tab** `\t` dari copy-paste Excel.
Berikut 24 nama header kolom yang didukung persis sesuai urutan sheet Excel kamu:
1. `ID INVENTARIS`
2. `LOKASI`
3. `RUANGAN`
4. `TYPE ASSET`
5. `DEPARTEMEN`
6. `PENGGUNA` (Utama)
7. `PENGGUNA 2` (Pendamping)
8. `ID MONITOR`
9. `MONITOR`
10. `TYPE/MODEL UNIT`
11. `SERIAL NUMBER`
12. `PROCESSOR`
13. `RAM`
14. `HDD`
15. `SSD`
16. `VGA CARD`
17. `HARGA`
18. `BULAN PEMBELIAN`
19. `TAHUN PEMBELIAN`
20. `STATUS`
21. `KONDISI`
22. `KETERANGAN`
23. `GARANSI`
24. `PRIORITAS` / `AKSI`

---

## 🛠️ 4. Perintah Pemeliharaan (Maintenance Commands)

- **Clear Cache Aplikasi**:
  ```powershell
  php artisan optimize:clear
  ```
- **Buat Symlink Storage (Attachment Files)**:
  ```powershell
  php artisan storage:link
  ```
