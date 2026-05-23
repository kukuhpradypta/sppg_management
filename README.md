# SPPG Management System

Aplikasi manajemen distribusi makanan **Satuan Pelayanan Pemenuhan Gizi (SPPG)** berbasis web menggunakan CodeIgniter 4. Sistem ini digunakan untuk mengelola dan memonitoring distribusi makanan harian dari SPPG ke sekolah-sekolah.

## Fitur

- **Dashboard Monitoring** — Statistik distribusi, grafik, dan ringkasan harian
- **Manajemen Sekolah** — CRUD data sekolah penerima (Admin)
- **Manajemen SPPG** — CRUD data unit SPPG (Admin)
- **Menu Harian** — CRUD menu makanan dengan upload foto
- **Distribusi** — Input dan tracking distribusi makanan ke sekolah
- **Role-based Access** — Admin (full access) dan SPPG (akses terbatas)
- **AJAX Modal** — Semua form create/edit menggunakan modal tanpa refresh halaman

## Tech Stack

- **Backend:** CodeIgniter 4.7, PHP 8.1+
- **Database:** MySQL 8.0 / MariaDB
- **Frontend:** Tailwind CSS (CDN), Material Symbols Icons
- **Font:** Inter (Google Fonts)

## Persyaratan

- PHP 8.1+ dengan extension: intl, mbstring, json, mysqlnd, curl
- MySQL 8.0+ / MariaDB 10.6+
- Composer

## Instalasi & Menjalankan

### 1. Clone / Download project

```bash
cd "folder-project"
```

### 2. Install dependencies

```bash
composer install
```

### 3. Konfigurasi database

Buat database MySQL:

```sql
CREATE DATABASE sppg_management CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
```

Edit file `.env` sesuai konfigurasi database kamu:

```
database.default.hostname = localhost
database.default.database = sppg_management
database.default.username = root
database.default.password =
database.default.DBDriver = MySQLi
database.default.port = 3306
```

### 4. Jalankan Migration

```bash
php spark migrate
```

### 5. Jalankan Seeder (data awal)

```bash
php spark db:seed InitialSeeder
```

### 6. Jalankan server

```bash
php spark serve --port 8081
```

Buka browser: **http://localhost:8081**

## Akun Demo

| Role | Username | Password |
|------|----------|----------|
| Admin | `admin` | `admin123` |
| SPPG Kebayoran | `sppg_kebayoran` | `sppg123` |
| SPPG Tebet | `sppg_tebet` | `sppg123` |

## Struktur Database

| Tabel | Keterangan |
|-------|-----------|
| `users` | Data user dengan role (admin/sppg) |
| `sppg` | Data unit Satuan Pelayanan Pemenuhan Gizi |
| `sekolah` | Data sekolah penerima distribusi |
| `menu_harian` | Menu makanan harian per SPPG (+ foto) |
| `distribusi` | Catatan distribusi makanan ke sekolah |

## Hak Akses

| Fitur | Admin | SPPG |
|-------|:-----:|:----:|
| Dashboard | ✅ Semua data | ✅ Data sendiri |
| CRUD Sekolah | ✅ | ❌ |
| CRUD SPPG | ✅ | ❌ |
| CRUD Menu | ✅ Semua | ✅ Milik sendiri |
| Input Distribusi | ✅ Semua | ✅ Milik sendiri |
| Hapus Distribusi | ✅ | ❌ |

## Upload Foto Menu

Foto menu disimpan di `public/menu/{nama_sppg}/` dengan format:
```
menu_{tanggal}_{timestamp}.{ext}
```

Contoh: `public/menu/sppg_kebayoran_baru/menu_2026-05-23_1716443581.png`
