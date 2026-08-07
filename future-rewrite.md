# Proposal & Blueprint Rewrite: Custom AMS (Asset Management & Helpdesk System)

## 📌 Executive Summary & Latency Analysis
Saat ini, tumpukan teknologi berbasis **Laravel 13 + Filament v3 + Livewire SSR** memerlukan waktu muat (*load time*) hingga **~10 detik** pada server Cloud VPS dengan RAM 2GB. Hal ini terjadi karena:
1. **Livewire DOM Diffing & Re-rendering Overhead**: Setiap interaksi Livewire mengirimkan seluruh payload state dan melakukan re-render komponen di server-side (PHP-FPM worker) secara synchronous.
2. **Memori & Process Footprint PHP-FPM**: PHP-FPM memerlukan pembuatan alokasi proses baru untuk setiap request HTTP/Livewire socket update.
3. **Heavy Dependency Loading**: Filament v3 memuat pustaka UI Blade & Livewire secara komprehensif pada server-side.

**Solusi Jangka Panjang**: Melakukan *rewrite* arsitektur menjadi **Single Page Application (SPA) Decoupled Architecture** berbasis **React + Vite + TailwindCSS** di sisi *Frontend*, dan **Node.js (Express / Hono) + PostgreSQL** di sisi *Backend API*.

---

## 🛠️ Proposed Technology Stack

### 1. Frontend (Client-Side SPA)
- **Framework**: [React 18/19](https://react.dev/) + [Vite](https://vitejs.dev/) (Build tool ultra-cepat berbasis ESM).
- **Styling System**: [TailwindCSS v3/v4](https://tailwindcss.com/) untuk desain UI modern, responsif, dan ultra-ringan.
- **UI Component Library**: [Shadcn UI](https://ui.shadcn.com/) / [Radix UI](https://www.radix-ui.com/) + Lucide React Icons.
- **State Management & Caching API**: [TanStack Query v5 (React Query)](https://tanstack.com/query/latest) untuk pencarian data instan (0ms cached navigation).
- **Form & Validation**: `react-hook-form` + `zod`.
- **Routing**: `react-router-dom` v6/v7.

### 2. Backend (REST API Service)
- **Runtime Environment**: [Node.js](https://nodejs.org/) (LTS Version 20/22) atau [Bun](https://bun.sh/).
- **API Framework**: [Express.js](https://expressjs.com/) atau [Hono.js](https://hono.dev/) (Framework mikro-performa tinggi).
- **Database ORM**: [Prisma ORM](https://www.prisma.io/) / [Drizzle ORM](https://orm.drizzle.team/).
- **Database Engine**: PostgreSQL 14/16 (Menggunakan basis data `ams_prod` yang sudah ada).
- **Authentication**: JSON Web Token (JWT) berbasis `HttpOnly` Secure Cookie + RBAC (Role-Based Access Control) Middleware.
- **PDF Generation Service**: `@react-pdf/renderer` atau `puppeteer` / `pdfkit` untuk cetak Surat Serah Terima (Handover), Berita Acara, PPB/LBS, dan Stiker QR 103.

---

## 🚀 Perbandingan Performa: Current vs Proposed Architecture

| Parameter | Current Architecture (Laravel + Filament) | Proposed Architecture (React + Vite + Node.js) |
| :--- | :--- | :--- |
| **Initial Load Time** | ~5.0s - 10.0s | **< 300ms** (Static Assets via CDN/Vite) |
| **Page Navigation Latency** | 1.5s - 3.0s (Full SSR / Livewire roundtrip) | **0ms - 50ms** (Instant Client-Side Route) |
| **Penggunaan RAM VPS** | ~1.7 GB - 1.9 GB (PHP-FPM + Livewire State) | **~200 MB - 350 MB** (Node.js Runtime Lightweight) |
| **Payload Ukuran Data** | High (HTML DOM Reponse & Component State) | **Ultra Low** (Pure JSON API Payloads) |
| **User Experience (UX)** | Terasa berat saat klik button / tab | App-like, sangat responsif & tanpa jeda |

---

## 🏗️ Modul & Fitur yang Akan Di-rewrite

### 1. Module Auth & Multi-Role Panel
- `/login`: Form Login Tunggal Kustom (`/gondowangi/login`).
- Automatic Role Redirection:
  - Role `admin` / `it_staff` -> `/admin/dashboard`
  - Role `user` -> `/user/dashboard`
- Protected Route Guards berbasis JWT & Role.

### 2. User Service Portal (`/user`)
- **User Dashboard**: Overview tiket aktif saya, tiket selesai, quick button pelaporan baru.
- **IT Service Request Form**: Input kendala, lokasi, perangkat manual, upload bukti foto.
- **Ticket Tracking Table**: Filter status tiket, cetak tiket PDF langsung dari browser.

### 3. Admin & IT Staff Portal (`/admin`)
- **Dashboard & Analytical Widgets**: Grafik tiket harian, statistik aset per lokasi, SLA tracker.
- **Aset Management**: Master data aset, histori checkout/checkin, cetak Stiker Barcode/QR 103.
- **IT Service Desk Desk**: Pengelolaan tiket, penugasan teknisi IT, penyesuaian SLA & status.
- **Pengajuan & Procurement (PPB / LBS)**: Form pengajuan aset baru, kalkulasi biaya tambahan, cetak PDF PPB/LBS.
- **Berita Acara & Disposal**: Berita acara perbaikan, penonaktifan aset (disposal), cetak form resmi PDF.

---

## 📅 Rencana Eksekusi Tahapan (Migration Roadmap)

### Tahap 1: API & Database Schema Setup (1-2 Minggu)
1. Setup Node.js Project dengan TypeScript & Express/Hono.
2. Generate Prisma/Drizzle Schema dari Database PostgreSQL `ams_prod` yang sudah ada.
3. Buat REST API Endpoints untuk Auth (`/api/auth`), Aset (`/api/assets`), Tiket (`/api/tickets`), dan Procurement (`/api/pengajuan`).

### Tahap 2: Setup Frontend React + Vite + Tailwind (1 Minggu)
1. Inisialisasi React Vite Project (`npm create vite@latest frontend -- --template react-ts`).
2. Install TailwindCSS, Shadcn UI components, Lucide Icons, dan TanStack Query.
3. Buat Axios/Fetch Client dengan interceptor token auth.

### Tahap 3: Migration User Portal (`/user`) (1 Minggu)
1. Implementasi UI Dashboard User & Form Pelaporan Tiket Baru.
2. Integrasi API Tiket User & Cetak PDF Client-side.

### Tahap 4: Migration Admin Portal (`/admin`) & PDF Engine (2 Minggu)
1. Implementasi UI Dashboard Admin, Tabel Aset, dan Service Desk IT Staff.
2. Migrasi Template Cetak PDF (Handover, Return, Berita Acara, PPB, LBS, Stiker 103) ke PDF Generator Engine Node.js.

### Tahap 5: Deployment & Benchmarking (3 Hari)
1. Deploy Node.js API Service via PM2 di VPS.
2. Build Static Bundle React (`npm run build`) dan serve via Nginx.
3. Lakukan benchmark latensi & konsumsi RAM server.
