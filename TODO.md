# AMS Development Plan - TODO

## Fase 1: Setup Project & Database
- [x] Cek environment (PHP, Composer, PostgreSQL)
- [x] Aktifkan ekstensi PHP (pdo_pgsql, pgsql, zip)
- [x] Buat database PostgreSQL `ams_dev`
- [x] Buat user PostgreSQL `ams_user`
- [x] Create project Laravel
- [x] Setup .env untuk PostgreSQL

## Fase 2: Install Filament & Copy Scaffold
- [x] Install filament/filament (v3.3.54)
- [x] Jalankan filament:install --panels
- [x] Copy migrations dari scaffold ke project
- [x] Copy Models dari scaffold ke project
- [x] Copy Filament Resources dari scaffold ke project
- [x] Jalankan migrate
- [x] Buat admin user (make:filament-user)

## Fase 3: Import CSV & JSON (Priority 1)
- [ ] Buat Import Resource & page di Filament
- [ ] Buat ImportService untuk CSV
- [ ] Buat ImportService untuk JSON
- [ ] Support import: Categories, Locations, Models, Assets
- [ ] Validasi & error handling

## Fase 4: Consumables & Components (Priority 2)
- [ ] Buat migration consumables
- [ ] Buat migration components
- [ ] Buat Model Consumable
- [ ] Buat Model Component
- [ ] Buat Filament Resource Consumable
- [ ] Buat Filament Resource Component
- [ ] Relasi dengan Category

## Fase 5: Custom Fields per Category (Priority 3)
- [ ] Buat migration custom_field_definitions (JSONB)
- [ ] Buat migration custom_field_values (JSONB)
- [ ] Buat Model CustomFieldDefinition
- [ ] Buat Model CustomFieldValue
- [ ] Integrasi ke Asset form (dynamic fields based on category)
- [ ] Tampilkan di tabel Asset

## Fase 6: Role & Permission (Priority 4)
- [ ] Install spatie/laravel-permission
- [ ] Buat seeder untuk roles & permissions
- [ ] Integrasi dengan Filament
- [ ] Middleware/guard di Resources
