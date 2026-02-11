# Aplikasi Peminjaman Alat (Pra Ujikom)

Aplikasi ini dibuat dengan Laravel 12 untuk skenario pra ujikom dengan 3 level pengguna:
- `admin`
- `petugas`
- `peminjam`

## Fitur Utama

### Admin
- Login / logout
- CRUD user
- CRUD kategori
- CRUD alat
- CRUD data peminjaman
- CRUD data pengembalian
- Log aktivitas

### Petugas
- Login / logout
- Menyetujui / menolak peminjaman
- Monitoring pengembalian
- Melihat dan mencetak laporan

### Peminjam
- Login / logout
- Melihat daftar alat
- Mengajukan peminjaman
- Mengajukan pengembalian alat

## Teknologi
- PHP 8.2+
- Laravel 12
- SQLite (default) atau MySQL

## Cara Menjalankan

1. Install dependency:
```bash
composer install
```

2. Pastikan file environment tersedia:
```bash
copy .env.example .env
php artisan key:generate
```

3. Jalankan migrasi + seeder:
```bash
php artisan migrate:fresh --seed
```

4. Jalankan server:
```bash
php artisan serve
```

5. Buka di browser:
- `http://127.0.0.1:8000`

## Akun Default

Password semua akun: `password123`

- Admin:
  - Email: `admin@ukk.local`
- Petugas:
  - Email: `petugas@ukk.local`
- Peminjam:
  - Email: `peminjam@ukk.local`

## Struktur Modul

- Routing: `routes/web.php`
- Middleware role: `app/Http/Middleware/RoleMiddleware.php`
- Controller:
  - Admin: `app/Http/Controllers/Admin`
  - Petugas: `app/Http/Controllers/Petugas`
  - Peminjam: `app/Http/Controllers/Peminjam`
- Views: `resources/views`
- Migration: `database/migrations`
- Seeder: `database/seeders/DatabaseSeeder.php`

## Catatan

- Setiap aktivitas penting (login, CRUD, approval, pengembalian) dicatat ke tabel `activity_logs`.
- Stok alat berkurang saat peminjaman disetujui dan bertambah kembali saat pengembalian diverifikasi.
