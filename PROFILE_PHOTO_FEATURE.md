# Profile Photo Feature

## Overview
Fitur foto profil telah ditambahkan ke aplikasi UMKMBid, memungkinkan setiap user untuk memiliki foto profil mereka sendiri.

## Features Implemented

### 1. Database Migration
- **File**: `database/migrations/2025_01_20_000000_add_profile_photo_to_users_table.php`
- Menambahkan kolom `profile_photo` ke tabel `users`
- Kolom bersifat nullable untuk backward compatibility

### 2. User Model Updates
- **File**: `app/Models/User.php`
- Menambahkan `profile_photo` ke `$fillable` array
- Method `getProfilePhotoUrl()`: Mengembalikan URL foto profil atau default avatar
- Method `getInitials()`: Menghasilkan inisial untuk default avatar

### 3. Profile Controller Updates
- **File**: `app/Http/Controllers/ProfileController.php`
- Update method `update()` untuk handle upload foto profil
- Method `deleteProfilePhoto()` untuk menghapus foto profil
- Validasi file: image, max 2MB, format jpeg/png/jpg/gif

### 4. Routes
- **File**: `routes/web.php`
- Route baru: `DELETE /profile/photo` untuk menghapus foto profil

### 5. View Updates

#### Profile Edit Form
- **File**: `resources/views/profile/edit.blade.php`
- Form upload foto profil dengan preview
- Tombol hapus foto profil (jika ada)
- JavaScript untuk preview gambar sebelum upload
- AJAX untuk menghapus foto profil

#### Profile Show Page
- **File**: `resources/views/profile/show.blade.php`
- Menampilkan foto profil di main profile card

#### Navigation Bar
- **File**: `resources/views/layouts/app.blade.php`
- Menampilkan foto profil kecil di navigation bar

### 6. Storage Structure
- **Directory**: `storage/app/public/profile-photos/`
- Foto profil disimpan dengan nama unik
- Foto lama dihapus otomatis saat upload foto baru

## Default Avatar System
Jika user belum upload foto profil, sistem akan menampilkan default avatar menggunakan:
- Service: UI Avatars (https://ui-avatars.com/)
- Format: Inisial nama user dengan background biru
- Fallback yang reliable dan tidak memerlukan storage lokal

## File Validation
- **Format**: JPEG, PNG, JPG, GIF
- **Ukuran maksimal**: 2MB
- **Validasi**: Server-side validation di ProfileController

## Security Features
- CSRF protection untuk semua form
- File type validation
- File size limitation
- Automatic cleanup of old profile photos

## Usage Instructions

### Upload Foto Profil
1. Login ke akun
2. Klik nama user di navigation bar atau kunjungi halaman profil
3. Klik "Edit Profil"
4. Pilih file gambar di bagian "Foto Profil"
5. Preview akan muncul otomatis
6. Klik "Simpan Perubahan"

### Hapus Foto Profil
1. Di halaman edit profil
2. Klik tombol "Hapus Foto Profil" (jika ada foto)
3. Konfirmasi penghapusan
4. Foto akan dihapus dan kembali ke default avatar

## Technical Notes

### Storage Link
Pastikan storage link sudah dibuat:
```bash
php artisan storage:link
```

### Migration
Jalankan migration untuk menambahkan kolom profile_photo:
```bash
php artisan migrate
```

### File Permissions
Pastikan direktori storage memiliki permission yang tepat untuk write operations.

## Future Enhancements
- Image resizing/cropping functionality
- Multiple image formats support
- Profile photo history
- Bulk user photo management for admin