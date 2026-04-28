# TMJ Integrated Operations System
### PT. TRI MACHMUD JAYA

Sistem manajemen operasional terintegrasi yang dirancang untuk mengelola absensi tenaga kerja, kesehatan karyawan, dan monitoring armada (fleet) secara real-time.

---

## 🚀 Fitur Utama
- **Absensi Terintegrasi:** Pencatatan kehadiran dengan parameter kesehatan (Tekanan Darah, SPO2, Suhu Tubuh).
- **Intelijen Armada:** Pemantauan status unit (Ready, Standby, Down) beserta pencatatan HM/KM yang dinamis.
- **Dashboard Admin Premium:** Visualisasi data operasional dengan grafik alokasi project dan monitoring status unit.
- **Simulasi Skala Besar:** Mendukung pengelolaan data hingga 100.000+ record dengan performa yang tetap optimal.

## 🛠️ Tech Stack
- **Framework:** Laravel 13 (Latest)
- **Runtime:** PHP 8.3 (Locked)
- **Frontend:** TailwindCSS 4.0 & Alpine.js
- **Database:** MySQL / PostgreSQL
- **Build Tool:** Vite

## 📋 Prasyarat
- PHP >= 8.3
- Composer
- Node.js & NPM
- MySQL atau Database lainnya

## ⚙️ Instalasi
1. Clone repositori:
   ```bash
   git clone [url-repositori]
   ```
2. Instal dependensi PHP:
   ```bash
   composer install
   ```
3. Instal dependensi Frontend:
   ```bash
   npm install && npm run build
   ```
4. Salin file environment:
   ```bash
   cp .env.example .env
   ```
5. Generate kunci aplikasi:
   ```bash
   php artisan key:generate
   ```
6. Jalankan migrasi dan impor data master:
   ```bash
   php artisan migrate:fresh --seed
   ```

## 🧪 Simulasi Data
Untuk melakukan pengujian beban sistem (stress test), jalankan seeder simulasi:
```bash
php artisan db:seed --class=SimulationSeeder
```
*Catatan: Secara default akan menghasilkan data simulasi selama 1 tahun.*

---
© 2026 PT. TRI MACHMUD JAYA. All rights reserved.
