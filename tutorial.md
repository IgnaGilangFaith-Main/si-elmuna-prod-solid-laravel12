# Tutorial: Troubleshooting Spatie Permission Migration

## Problem 1: Cache Table Not Found

### Error Message

```
SQLSTATE[42S02]: Base table or view not found: 1146 Table 'si_elmuna.cache' doesn't exist
```

### Penyebab

Migration `create_permission_tables` mencoba menghapus cache dari tabel `cache` yang belum ada.

### Solusi

#### Opsi 1: Ubah Cache Store ke 'array' (Recommended untuk Development)

1. Publikasikan config permission:

```bash
php artisan vendor:publish --provider="Spatie\Permission\PermissionServiceProvider"
```

2. Edit `config/permission.php`:

```php
'cache' => [
    'store' => 'array', // ubah dari 'default' ke 'array'
    'key' => 'spatie.permission.cache',
],
```

#### Opsi 2: Buat Tabel Cache Terlebih Dahulu (Recommended untuk Production)

```bash
php artisan cache:table
php artisan migrate
```

#### Opsi 3: Modifikasi Migration dengan Try-Catch

Edit migration file, wrap bagian cache clearing:

```php
// Di akhir method up(), sebelum closing brace
try {
    app('cache')
        ->store(config('permission.cache.store') != 'default' ? config('permission.cache.store') : null)
        ->forget(config('permission.cache.key'));
} catch (\Exception $e) {
    // Cache table might not exist yet, ignore
}
```

---

## Problem 2: Table Already Exists

### Error Message

```
SQLSTATE[42S01]: Base table or view already exists: 1050 Table 'permissions' already exists
```

### Penyebab

Tabel permission sudah ada di database dari migration sebelumnya yang gagal atau sudah pernah dijalankan.

### Solusi

#### Opsi 1: Migrate Fresh (Hapus Semua Data)

⚠️ **WARNING**: Ini akan menghapus SEMUA data di database!

```bash
php artisan migrate:fresh
```

#### Opsi 2: Hapus Tabel Permission Manual via Tinker

```bash
php artisan tinker
```

Jalankan perintah berikut:

```php
DB::statement('SET FOREIGN_KEY_CHECKS=0;');
DB::statement('DROP TABLE IF EXISTS role_has_permissions');
DB::statement('DROP TABLE IF EXISTS model_has_roles');
DB::statement('DROP TABLE IF EXISTS model_has_permissions');
DB::statement('DROP TABLE IF EXISTS roles');
DB::statement('DROP TABLE IF EXISTS permissions');
DB::statement('SET FOREIGN_KEY_CHECKS=1;');
exit
```

Kemudian migrate lagi:

```bash
php artisan migrate
```

#### Opsi 3: Hapus Manual via phpMyAdmin

1. Buka phpMyAdmin: `http://localhost/phpmyadmin`
2. Pilih database `si_elmuna`
3. Hapus tabel-tabel berikut (urutan penting karena foreign key):

    - `role_has_permissions`
    - `model_has_roles`
    - `model_has_permissions`
    - `roles`
    - `permissions`

4. Jalankan migrate:

```bash
php artisan migrate
```

#### Opsi 4: Reset Migration Record

Hapus record migration dari tabel migrations:

```bash
php artisan tinker
```

```php
DB::table('migrations')->where('migration', 'like', '%create_permission_tables%')->delete();
exit
```

Kemudian gunakan Opsi 2 untuk hapus tabelnya, lalu migrate lagi.

---

## Best Practices

### 1. Sebelum Migrate Permission

-   Pastikan tabel cache sudah ada ATAU
-   Gunakan cache store 'array' untuk development

### 2. Jika Migration Gagal

-   Cek error messagenya
-   Jangan langsung migrate lagi tanpa membersihkan tabel yang sudah terbuat sebagian
-   Gunakan `migrate:fresh` untuk development
-   Gunakan `migrate:rollback` atau hapus manual untuk production

### 3. Urutan Penghapusan Tabel Permission

Selalu hapus dengan urutan ini (karena foreign key constraints):

1. `role_has_permissions` (pivot table)
2. `model_has_roles` (pivot table)
3. `model_has_permissions` (pivot table)
4. `roles` (main table)
5. `permissions` (main table)

### 4. Backup Database

Sebelum menjalankan `migrate:fresh`, selalu backup database terlebih dahulu:

```bash
# Backup via mysqldump
mysqldump -u root -p si_elmuna > backup_si_elmuna.sql

# Restore jika diperlukan
mysql -u root -p si_elmuna < backup_si_elmuna.sql
```

---

## Quick Commands Reference

```bash
# Publish permission config
php artisan vendor:publish --provider="Spatie\Permission\PermissionServiceProvider"

# Create cache table migration
php artisan cache:table

# Run migrations
php artisan migrate

# Reset all migrations (DANGER: DELETE ALL DATA)
php artisan migrate:fresh

# Rollback last migration
php artisan migrate:rollback

# Check migration status
php artisan migrate:status

# Open tinker console
php artisan tinker

# Clear all caches
php artisan optimize:clear
```

---

## Troubleshooting Checklist

-   [ ] Apakah tabel `cache` sudah ada?
-   [ ] Apakah config permission sudah dipublish?
-   [ ] Apakah cache store sudah diatur ke 'array' atau 'database'?
-   [ ] Apakah ada tabel permission yang sudah terbuat sebagian?
-   [ ] Apakah foreign key constraints menyebabkan error saat drop table?
-   [ ] Apakah sudah backup database sebelum migrate:fresh?

---

**Tanggal dibuat:** 28 Oktober 2025
**Project:** SI Elmuna Laravel 12
