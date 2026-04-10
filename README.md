# Sorlim - Sistem Monitoring Unit & Absensi PT. TRI MACHMUD JAYA

Sorlim (dulu dikenal sebagai Short Limp) adalah aplikasi berbasis web yang dirancang khusus untuk **PT. TRI MACHMUD JAYA**. Sistem ini berfungsi untuk mempermudah pemantauan status unit (kendaraan/alat berat) melalui QR Code dan pengelolaan absensi karyawan secara real-time.

## 🚀 Fitur Utama

- **Monitoring Unit Berbasis QR Code**: Laporkan status unit (Ready, Breakout, dll) hanya dengan memindai kode QR yang ada di unit.
- **Absensi Karyawan**: Sistem absensi mandiri untuk karyawan dengan antarmuka yang ramah pengguna.
- **Admin Dashboard**: Panel kontrol terpusat untuk memantau kehadiran, statistik unit, dan manajemen data.
- **Role-Based Access Control**: Pembagian akses berdasarkan peran (Admin, Supervisor, HRD, Workshop).
- **Interface Modern**: Menggunakan desain premium yang responsif untuk perangkat mobile maupun desktop.

## 📋 Prasyarat Sistem

Sebelum melakukan pemasangan, pastikan perangkat Anda telah terpasang:
- **PHP** >= 8.3
- **Composer** (Dependency Manager untuk PHP)
- **Node.js & NPM** (untuk pengelolaan aset frontend)
- **Database Server** (MySQL, MariaDB, atau SQLite)
- **Web Browser** modern (Chrome, Edge, Firefox)

## 🛠️ Langkah Pemasangan

Ikuti langkah-langkah berikut untuk memasang sistem di perangkat baru:

### 1. Kloning Repositori
```bash
git clone https://github.com/username/project-tmj.git
cd project-tmj
```

### 2. Jalankan Script Setup Otomatis
Proyek ini telah dilengkapi dengan script setup untuk memudahkan instalasi. Jalankan perintah berikut:
```bash
composer setup
```
*Script ini secara otomatis akan:*
- Menginstal dependensi PHP via Composer.
- Membuat file `.env` dari `.env.example`.
- Men-generate kunci aplikasi (`APP_KEY`).
- Menginstal dependensi frontend via NPM.
- Melakukan compile aset frontend.

### 3. Konfigurasi Database
Buka file `.env` yang baru dibuat, lalu sesuaikan konfigurasi database Anda:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=nama_database_anda
DB_USERNAME=root
DB_PASSWORD=
```

### 4. Migrasi & Seed Data
Jalankan perintah berikut untuk membuat struktur tabel dan mengisi data awal (unit, user, dll):
```bash
php artisan migrate --seed
```

## 💻 Menjalankan Aplikasi

Setelah pemasangan selesai, Anda dapat menjalankan server lokal dengan:

**Server Laravel:**
```bash
php artisan serve
```

**Development Mode (Vite):**
Jika Anda melakukan perubahan pada CSS/JS, jalankan:
```bash
npm run dev
```

Aplikasi dapat diakses melalui browser di alamat: `http://127.0.0.1:8000`

---

## 👥 Kontribusi
Hanya untuk penggunaan internal PT. TRI MACHMUD JAYA.

## 📄 Lisensi
Sistem ini bersifat privat dan hanya digunakan untuk kepentingan internal perusahaan.
