# 📋 IT TICKETING SYSTEM & AMS INTEGRATION SCAFFOLDING

> **Project**: PT Gondowangi Tradisional Kosmetika - Asset Management & IT Service System  
> **Concept**: Schedule-Driven IT Ticketing System with Integrated Asset Maintenance History  
> **Location**: Root Directory (`Ticketing-Scaffoldi.MD`)

---

## 🎯 Architecture & Workflow Overview

System alur kerja berbasis jadwal (**Scheduled-Driven IT Ticketing**):

```
[ 1. Tiket Masuk / Permintaan Layanan ]
                  │
                  ▼
       ┌─────────────────────┐
       │   IT TICKET (AMS)   │  No. Tiket: TCK/IT/2026/07/001
       └──────────┬──────────┘  Metode: Scheduled Date & Time Slot
                  │
                  ▼
       ┌─────────────────────┐
       │ JADWAL & PENGERJAAN │  Jadwal: 28 Juli 2026 (09:00 - 12:00)
       └──────────┬──────────┘  Petugas IT: Bambang Yulianto
                  │
        ┌─────────┴────────────────────────┐
        ▼                                  ▼
[ 2A. Need Replacement Part ]      [ 2B. Internal Repair / No Cost ]
        │                                  │
        ▼                                  ▼
 ┌──────────────┐                   ┌──────────────┐
 │ PENGAJUAN    │ (PPB & LBS)       │ BERITA ACARA │ (Perbaikan)
 │ ASET BARU    │                   │ IT           │
 └──────┬───────┘                   └──────┬───────┘
        │                                  │
        └─────────────────┬────────────────┘
                          │
                          ▼
              ┌───────────────────────┐
              │ 3. ASSET MAINTENANCE  │
              │    & LIFECYCLE LOG    │ (Recorded in Asset History)
              └───────────────────────┘
```

---

## 🗄️ Database Schemas & Relations

### 1. Table `tickets`
* `ticket_number`: `string` (Auto: `TCK/IT/YYYY/MM/XXX`, Unique)
* `reporter_name`: `string` (Nama Pelapor / User)
* `reporter_department`: `string` (Departemen Pelapor)
* `contact_number`: `string` (No. HP / Extension, Nullable)
* `location_id`: `foreignKey` -> `locations` (Lokasi Pengerjaan)
* `room`: `string` (Detail Ruangan)
* `asset_id`: `foreignKey` -> `assets` (Nullable, unit terikat)
* `asset_tag`: `string` (ID Inventaris)
* `asset_name`: `string` (Nama / Model Unit)
* `category`: `enum` (`hardware`, `software`, `network_wifi`, `printer_scanner`, `access_rights`, `scheduled_service`, `other`)
* `subject`: `string` (Judul Kendala / Permintaan)
* `description`: `text` (Detail Penjelasan)
* **`scheduled_date`**: `date` (Tanggal Rencana Pengerjaan IT)
* **`scheduled_time_slot`**: `string` (Jam Rencana Pengerjaan, e.g. `09:00 - 12:00`)
* `due_date`: `date` (Target Selesai / SLA)
* `priority`: `enum` (`low`, `medium`, `high`, `critical`)
* `assigned_to`: `foreignKey` -> `users` (Petugas IT)
* `assigned_to_name`: `string` (Nama IT Staff)
* `status`: `enum` (`open`, `scheduled`, `in_progress`, `pending_sparepart`, `resolved`, `closed`, `rescheduled`)
* `reschedule_reason`: `text` (Alasan Penundaan / Perubahan Jadwal)
* `resolution_notes`: `text` (Solusi & Hasil Pengerjaan IT)
* `resolved_at`: `datetime`
* `pengajuan_aset_id`: `foreignKey` -> `pengajuan_asets` (Nullable)
* `dispose_aset_id`: `foreignKey` -> `dispose_asets` (Nullable)
* `berita_acara_id`: `foreignKey` -> `berita_acaras` (Nullable)
* `attachments`: `json` (Tangkapan layar / foto kendala)

### 2. Table `asset_maintenance_logs`
* `asset_id`: `foreignKey` -> `assets`
* `ticket_id`: `foreignKey` -> `tickets` (Nullable)
* `pengajuan_aset_id`: `foreignKey` -> `pengajuan_asets` (Nullable)
* `dispose_aset_id`: `foreignKey` -> `dispose_asets` (Nullable)
* `berita_acara_id`: `foreignKey` -> `berita_acaras` (Nullable)
* `maintenance_type`: `enum` (`repair`, `sparepart_replacement`, `routine_service`, `upgrade`, `disposal`)
* `title`: `string`
* `description`: `text`
* `cost`: `decimal(15,2)`
* `performed_by`: `string` (Petugas IT)
* `performed_at`: `date`

---

## 📝 TODO CHECKLIST / IMPLEMENTATION PLAN

- [x] **Task 1: Migration & Models**
  - [x] Create `2025_01_01_000017_create_tickets_table.php`
  - [x] Create `2025_01_01_000018_create_asset_maintenance_logs_table.php`
  - [x] Create `app/Models/Ticket.php`
  - [x] Create `app/Models/AssetMaintenanceLog.php`
  - [x] Run `php artisan migrate`

- [x] **Task 2: Eloquent Model Relationships**
  - [x] Update `app/Models/Asset.php` (`hasMany` tickets & maintenanceLogs)
  - [x] Update `app/Models/PengajuanAset.php` (`belongsTo` ticket)
  - [x] Update `app/Models/DisposeAset.php` (`belongsTo` ticket)
  - [x] Update `app/Models/BeritaAcara.php` (`belongsTo` ticket)

- [x] **Task 3: Filament Resource - `TicketResource`**
  - [x] Create `app/Filament/Resources/TicketResource.php`
  - [x] Navigation Label: `IT Service Tickets`
  - [x] Navigation Icon: `heroicon-o-ticket`
  - [x] Navigation Sort: `4` (Placed prominently right after Assets/Asset History)
  - [x] Form Schema:
    - [x] Section 1: Identitas Pelapor & Lokasi
    - [x] Section 2: Pemilihan Asset IT (AMS Autocomplete)
    - [x] Section 3: Kendala & Kategori
    - [x] Section 4: Penjadwalan IT (`scheduled_date`, `time_slot`, `assigned_to`, `status`)
    - [x] Section 5: Solusi & Catatan IT
  - [x] Table View & Custom Badges:
    - [x] Status & Priority Badges
    - [x] Filter by Schedule Date (Hari Ini) & Status
  - [x] Header / Table Actions:
    - [x] Action **`Ubah Jadwal (Reschedule)`** (Modal rubah tanggal & alasan)
    - [x] Action **`Cetak Work Order (PDF)`**

- [x] **Task 4: Printable PDF Form (`ticket-form.blade.php`)**
  - [x] Create PDF Template `resources/views/pdf/ticket-form.blade.php`
  - [x] Add route `admin/tickets/{ticket}/pdf` and controller method `downloadTicket`
  - [x] Format: PT Gondowangi Official Work Order / IT Service Form layout (Solid Black Borders `#000`)

- [x] **Task 5: Asset Maintenance History Integration**
  - [x] Add relation manager / tab `Riwayat Tiket IT & Maintenance` in `AssetResource.php` (`TicketsRelationManager.php`)

---

*Last Updated: 27 July 2026 — Antigravity AI & IT Department PT Gondowangi*
