# TMJ Integrated Operations System (TIOS)
### PT. TRI MACHMUD JAYA

Sistem manajemen operasional terintegrasi kelas enterprise yang dirancang khusus untuk memfasilitasi kebutuhan PT. Tri Machmud Jaya dalam mengelola kehadiran (absensi) tenaga kerja, pemantauan kondisi kesehatan karyawan harian, serta monitoring status unit/armada berat (Fleet Management) secara *real-time* berbasis pemindaian QR Code dan pelacakan geolokasi (Geolocation Tracking).

---

## 🌟 Fitur Utama (Core Features)

### 1. Workforce Attendance & Health Monitoring
- **Pelacakan Geolokasi Presisi (New):** Sistem absensi kini mendeteksi letak koordinat (Latitude & Longitude) karyawan secara akurat. Sistem juga dapat memvalidasi jarak (*radius*) antara lokasi karyawan dengan titik koordinat area kerja (Site/Pit).
- **Pencatatan Kesehatan Harian:** Mengharuskan pekerja untuk menginput parameter kesehatan kritikal sebelum bekerja (Tekanan Darah, Tingkat Oksigen/SPO2, dan Suhu Tubuh).
- **Proteksi Spoofing:** Mendukung parameter tambahan untuk memvalidasi akurasi GPS dan mencegah pemalsuan lokasi (*mock location*).

### 2. Fleet & Heavy Equipment Management
- **QR Code Integration:** Inspeksi armada dan perubahan status dilakukan dengan cepat melalui pemindaian QR Code yang tertempel pada masing-masing unit/alat berat.
- **Dynamic Status Tracking:** Pencatatan status operasional (Ready, Standby, Breakdown/Down) yang terpusat.
- **HM/KM Logging:** Pencatatan Hour Meter (HM) atau Kilometer (KM) secara dinamis saat pergantian *shift* atau *handover* unit.

### 3. Role-Based Admin Dashboard
- **Multi-level Access:** Sistem otentikasi aman yang membagi akses ke beberapa peran khusus:
  - **Admin:** Akses penuh ke seluruh sistem.
  - **HRD:** Akses khusus ke data pekerja, laporan absensi harian, dan kesehatan karyawan.
  - **Supervisor/Workshop:** Akses khusus ke monitoring armada dan pergerakan unit.
- **Visualisasi Data Real-Time:** Menampilkan grafik statistik kehadiran, alokasi project, dan performa armada (Unit Availability).

### 4. High Performance & Scalability
- **Large Dataset Handling:** Dioptimalkan dengan *indexing* tingkat lanjut untuk mampu menangani dan mensimulasikan lebih dari 100.000+ data *record* tanpa penurunan performa.

---

## 🛠️ Teknologi yang Digunakan (Tech Stack)

Sistem ini dikembangkan menggunakan tumpukan teknologi modern untuk memastikan performa yang cepat, aman, dan responsif.

- **Backend Framework:** Laravel 13
- **Bahasa Pemrograman:** PHP 8.3
- **Frontend Framework:** TailwindCSS v4.0 & Alpine.js v3
- **Bundler:** Vite
- **Database:** MySQL / PostgreSQL

---

## 📋 Persyaratan Sistem (Prerequisites)

Sebelum melakukan instalasi, pastikan server atau mesin lokal Anda memiliki perangkat lunak berikut:
- PHP >= 8.3
- Composer (Versi 2.x)
- Node.js (Versi 18+ disarankan) & NPM
- MySQL Server (atau PostgreSQL)
- Git

---

## ⚙️ Panduan Instalasi (Local Development)

Ikuti langkah-langkah di bawah ini untuk menjalankan *TMJ Integrated Operations System* di komputer lokal Anda:

1. **Clone Repositori**
   ```bash
   git clone https://github.com/almus-06/project-tmj.git
   cd project-tmj
   ```

2. **Install Dependensi Backend (PHP)**
   ```bash
   composer install
   ```

3. **Install Dependensi Frontend (Node)**
   ```bash
   npm install
   npm run build
   ```

4. **Konfigurasi Environment**
   Salin file konfigurasi bawaan dan sesuaikan kredensial database Anda (DB_DATABASE, DB_USERNAME, DB_PASSWORD).
   ```bash
   cp .env.example .env
   ```

5. **Generate Application Key**
   ```bash
   php artisan key:generate
   ```

6. **Migrasi Database & Seeding Data Master**
   Perintah ini akan membuat struktur tabel baru (termasuk tabel pendukung fitur Geolocation) dan mengisi data *dummy/master*.
   ```bash
   php artisan migrate:fresh --seed
   ```

7. **Jalankan Development Server**
   ```bash
   php artisan serve
   ```
   Aplikasi dapat diakses melalui `http://localhost:8000`.

---

## 🚀 Panduan Deployment (VPS / Production Server)

Jika Anda ingin melakukan update atau *pull* pembaruan (terutama branch `feature/geolocation-tracing`) ke dalam VPS/Server yang sudah berjalan:

1. Masuk ke direktori project di dalam VPS Anda:
   ```bash
   cd /var/www/project-tmj
   ```

2. Ambil update dari repository:
   ```bash
   git fetch origin
   git checkout feature/geolocation-tracing
   # atau `git pull origin feature/geolocation-tracing` jika sudah berada di branch tersebut
   ```

3. Update dependensi, *build* *assets*, dan *migrate* database:
   ```bash
   npm install
   npm run build
   composer install --optimize-autoloader --no-dev
   php artisan migrate --force
   ```

4. Bersihkan Cache aplikasi Laravel:
   ```bash
   php artisan optimize:clear
   ```

---

## 🧪 Simulasi Data (Stress Test)

Untuk melakukan pengujian beban sistem atau melihat contoh *real* dari banyak data, jalankan *seeder* simulasi berikut:
```bash
php artisan db:seed --class=SimulationSeeder
```
*Catatan: Secara default akan menghasilkan data simulasi transaksi kehadiran & unit selama 1 tahun ke belakang. Proses ini mungkin memakan waktu beberapa menit tergantung spesifikasi mesin.*

---

## 🛡️ Lisensi & Hak Cipta

© 2026 **PT. TRI MACHMUD JAYA**. All rights reserved.
Sistem ini merupakan properti eksklusif dari PT. Tri Machmud Jaya dan tidak untuk didistribusikan secara publik tanpa izin resmi.
