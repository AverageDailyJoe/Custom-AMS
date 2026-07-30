# 🚀 AMS Future Scaffolding & Strategic Roadmap
**PT Gondowangi Tradisional Kosmetika - IT Asset & Service Management System**

Dokumen ini berfungsi sebagai peta jalan (*roadmap*), analisis prioritas pengembangan, serta rancangan fitur masa depan (*Future-Proof & Fool-Proof*) untuk sistem AMS.

---

## 📊 Matriks Prioritas Pengembangan (Priority Ranking)

Berdasarkan urgensi operasional di lapangan (*Head Office, Cikarang, Surabaya, & Kebutuhan Audit GM Finance/GA*), berikut adalah urutan prioritas yang direkomendasikan:

| Prioritas | Fitur Utama | Kategori | Tingkat Urgensi | Estimasi Dampak |
| :--- | :--- | :--- | :--- | :--- |
| ✅ **PRIORITY 1** | **1-Click Rekapitulasi Laporan Aset & Servis (Audit-Ready)** | Audit-Ready | **COMPLETED** | Siap 100% untuk audit mendadak & audit tahunan (PDF & Excel) |
| ✅ **PRIORITY 2** | **Hitung Penyusutan Depresiasi Aset (Depreciation Calculator)** | Future-Proof | **COMPLETED** | Standar Akuntansi Global 4 Thn (25%/thn) & Nilai Buku Sisa |
| ✅ **PRIORITY 3** | **Smart Auto-Fill & Lock Status Aset (Fool-Proof Form)** | Fool-Proof | **COMPLETED** | Auto-Fill Data Pelapor, Catatan Lokasi Temporary & Status Badge |
| ✅ **PRIORITY 4** | **Preventive Maintenance Scheduler (Jadwal Rutin Servis)** | Future-Proof | **COMPLETED** | Siklus 3/6 Bulan, Widget Dashboard & 1-Click Tiket Maintenance |
| 🏷️ **PRIORITY 5** | **Cetak Stiker Tag Aset (Brother QL-800 / Tom & Jerry)** | Fool-Proof | **FLEKSIBEL** | Audit fisik & verifikasi unit dengan stiker label |
| 📦 **PRIORITY 6** | **Sparepart Inventory Tracker (Stok Kabel & Komponen)** | Efficiency | **OPSIONAL** | Pencatatan stok kabel VGA/HDMI/RAM/SSD |

---

## 🔍 Rincian Spesifikasi & Desain Fitur Per Prioritas

### 🔥 PRIORITY 1: 1-Click Rekapitulasi Laporan Aset & Servis (Audit-Ready)
> **Tujuan**: Memungkinkan staf IT mencetak seluruh laporan rekapitulasi secara instan dalam 1 klik saat ada audit tahunan maupun audit mendadak dari internal/eksternal.

#### Fitur & Format Output:
1. **Laporan Rekapitulasi Aset Aktif per Lokasi**:
   - Filter lokasi: All, HQ / Head Office, Factory Jababeka (Cikarang), Gudang Surabaya.
   - Format PDF resmi menggunakan standar **solid black border** (`1px solid #000`).
2. **Laporan Histori Perbaikan & Biaya IT (PPB/LBS Summary)**:
   - Rekap total biaya pengeluaran perbaikan & pengadaan aset per periode (Bulanan / Tahunan).
3. **Ekspor Excel Multi-Tab**:
   - Ekspor data mentah ke Excel untuk kebutuhan pengolahan data akuntansi GA/Finance.

---

### ⚡ PRIORITY 2: Hitung Penyusutan Depresiasi Aset (Depreciation Calculator)
> **Tujuan**: Menghitung sisa nilai ekonomi (nilai buku) aset dari harga beli (`purchase_cost`) dan tahun pembelian (`purchase_year`) menggunakan metode garis lurus (*Straight-Line Depreciation*).

#### Aturan & Rumus Depresiasi Berdasarkan Jenis Perangkat:
- 💻 **Laptop / Notebook**: **20% per tahun** (Masa manfaat 5 tahun).
- 🖥️ **PC Desktop / Workstation**: **15% per tahun** (Masa manfaat 6.6 tahun).
- 🖨️ **Printer / Scanner**: **15% per tahun** (Masa manfaat 6.6 tahun).
- 🖥️ **Monitor Display**: **10% per tahun** (Masa manfaat 10 tahun).
- 🌐 **Network / Server Hardware**: **10% per tahun** (Masa manfaat 10 tahun).

#### Manfaat Operasional:
- Saat menyusun **Formulir Pengajuan Aset Rusak (Disposal Aset IT)**, sistem otomatis menampilkan *Nilai Buku Sisa (Rp)* sehingga mempercepat persetujuan GA & Direksi.

---

### ⚡ PRIORITY 3: Smart Auto-Fill & Lock Status Aset (Fool-Proof Form)
> **Tujuan**: Mencegah salah ketik data karyawan di formulir Tiket IT dan mengunci status aset yang sedang bermasalah/afkir.

#### Rincian Logika:
1. **Auto-Fill Data Pelapor pada Tiket IT**:
   - Ketika IT memilih ID Aset (misal: `GTK-02-03-21`), sistem otomatis mengisi field *Nama Pelapor*, *Departemen*, *Lokasi Utama*, dan *Ruangan* berdasarkan data checkout aktif terakhir unit tersebut.
2. **Kunci Status Aset (Status Lock Validation)**:
   - Aset yang berstatus `in_repair` (sedang diperbaiki) atau `disposed` (rusak/afkir) **tidak bisa di-checkout** ke karyawan baru untuk mencegah konflik data.

---

### 📅 PRIORITY 4: Preventive Maintenance Scheduler (Pengingat Maintenance Rutin)
> **Tujuan**: Memastikan perangkat IT kritikal di Pabrik Cikarang & Gudang Surabaya mendapatkan perawatan berkala sebelum terjadi kelambatan/downtime produksi.

#### Siklus Maintenance Rutin:
- **Server & Network Switch**: Setiap **3 Bulan** (Check temperature, backup log, clean dust).
- **PC Line Produksi & Gudang**: Setiap **6 Bulan** (Clean dust, thermal paste, defrag/disk check, update antivirus).
- **Notifikasi Dashboard**: Widget pengingat otomatis saat jadwal servis berkala sudah memasuki H-7.

---

### 🏷️ PRIORITY 5: Cetak Stiker Tag Aset (Brother QL-800 / Label Tom & Jerry)
> **Tujuan**: Menyediakan opsi cetak stiker fisik untuk ditempelkan pada fisik unit PC/Laptop/Printer.

#### Metode Cetak Dua Mode:
1. **Mode Roll Label Printer (Brother QL-800)**:
   - Ukuran label continuous roll 62mm / 29mm x 90mm.
2. **Mode Lembar Kertas Label Standard (Tom & Jerry No. 108 / 121)**:
   - Template grid untuk dicetak menggunakan printer standar (EPSON / CANON / HP) menggunakan kertas stiker label Tom & Jerry yang terjangkau.
3. **Informasi Pada Stiker**:
   - Logo PT Gondowangi, Asset Tag (`GTK-02-03-21`), QR Code, dan Nama Model Unit.

---

### 📦 PRIORITY 6: Sparepart Inventory Tracker (Opsional)
> **Tujuan**: Pencatatan persediaan komponen & kabel IT hasil pembelian PPB/LBS.

#### Komponen Utama:
- Pencatatan stok: *Kabel VGA, Kabel HDMI, RAM DDR4, SSD M.2 NVMe, Adapter Mikrotik, Mouse Wireless*.
- Pengurangan otomatis saat tiket perbaikan dinyatakan `Resolved`.

---

## 📝 Catatan Diskusi & Status Implementasi
- 📌 **Status Current Phase**: Phase 1 IT Service Tickets & Printed Form Layout Complete.
- 📌 **Next Discussion Phase**: Review dan konfirmasi eksekusi **Priority 1 (1-Click Audit Rekapitulasi Laporan)** & **Priority 2 (Kalkulator Depresiasi Aset)**.

---

## 📅 Update Roadmap & Refinement (30 Juli 2026 GMT +7)

Berdasarkan penyesuaian kebutuhan operasional IT dan efisiensi anggaran, berikut adalah keputusan refinement fitur:

### 1. 🏷️ Cetak Stiker Tag Aset (QR Code - Focus: Label Tom & Jerry No. 121)
- **Fokus Utama**: Mengingat anggaran IT terbatas, fitur cetak stiker difokuskan pada lembar kertas stiker label **Tom & Jerry No. 121** (atau ukuran standar yang ekonomis) menggunakan printer standar kantor (EPSON / CANON / HP).
- **Elemen Stiker**: Asset Tag (`GTK-XX-XX`), QR Code unik untuk verifikasi cepat, Nama Model Unit, dan Identitas PT Gondowangi.

### 2. 🔑 Manajemen Lisensi Software (Software Licenses Tracker)
- **Cakupan Software**: Pencatatan lisensi perangkat lunak perusahaan secara terpusat, tidak terbatas pada Microsoft 365, tetapi mencakup:
  - **Microsoft 365 & Windows OEM**
  - **Desain & Media**: Adobe Creative Cloud (Photoshop, Illustrator, Premiere), Canva Pro, CorelDRAW.
  - **Aplikasi Lainnya**: Antivirus, CAD, Utility & Tools IT.
- **Metrik Tracking**: Jumlah *seats* terpakai vs kuota, tanggal kadaluarsa (*expiration alert*), kunci lisensi (*product key*), dan penugasan lisensi ke unit aset/karyawan.

### 3. 🧩 Sparepart Inventory Tracker (Fokus Komponen Hardware Unit)
- **Fokus Scope**: Terbatas pada komponen hardware/sparepart pengganti internal (tidak mencakup barang konsumsi umum):
  - **Storage**: SSD (M.2 NVMe, SATA) & Harddisk.
  - **Memory**: RAM (DDR3 / DDR4 / DDR5).
  - **Body Parts**: Casing Laptop, Engsel Replacement.
  - **Power & Board**: Baterai Laptop, Baterai CMOS, Adaptor Charger.
- **Fitur Integrasi**: Pencatatan stok masuk/keluar & link penggunaan sparepart pada Tiket Perbaikan IT.

### 🚫 Non-Scope Clarification:
- **Digital Signature**: **Tidak dibutuhkan** (penandatanganan fisik pada cetakan dokumen Berita Acara PDF yang ada saat ini sudah memenuhi standar operasional & audit).

