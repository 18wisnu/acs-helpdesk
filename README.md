# Tanjungpura Net - Parameter HelpDesk

Sistem Manajemen Perangkat dan HelpDesk untuk integrasi GenieACS dan Monitoring Jaringan (Tanjungpura Network).

## 🚀 Fitur Utama
- **Dashboard Monitoring:** Status kesehatan modem real-time (Good, Warning, Critical, Offline).
- **Manajemen Site & ODP:** Kelola lokasi OLT (Site) dan titik bagi splitter (ODP).
- **Konfigurasi Modem:** Ubah SSID/Password Wifi (Dual-Band), update PPPoE, Reboot, dan Diagnostik langsung via GenieACS.
- **Portal Pelanggan:** Pendaftaran pelanggan otomatis membuat akun login portal client.
- **Peta Satelit:** Visualisasi lokasi site dan ODP pada peta.

## 📋 Prasyarat Sistem (Requirements)
Sebelum menjalankan aplikasi ini, pastikan server Anda memiliki:
- **PHP >= 8.2** (disarankan bit 64-bit pada Windows/Laragon)
- **Composer** (untuk dependensi PHP)
- **Node.js & NPM** (untuk build Tailwind/Vite)
- **Database:** MySQL 5.7+ atau MariaDB 10.3+
- **GenieACS:** Server GenieACS yang aktif dan dapat dijangkau API-nya (default port 7557).

## 🛠️ Cara Instalasi
1. **Clone project:**
   ```bash
   git clone [url-repository]
   cd acs-helpdesk
   ```
2. **Install dependensi:**
   ```bash
   composer install
   npm install
   npm run build
   ```
3. **Konfigurasi Environment:**
   Salin file `.env.example` menjadi `.env` dan atur:
   ```env
   DB_DATABASE=helpdesk-acs
   DB_USERNAME=root
   DB_PASSWORD=
   
   GENIEACS_URL=http://[ip-server-acs]:7557
   ```
4. **Generate Key & Migrasi Database:**
   ```bash
   php artisan key:generate
   php artisan migrate
   ```
5. **Jalankan Aplikasi:**
   Gunakan Laragon atau jalankan manual:
   ```bash
   php artisan serve
   ```

## 🔒 Catatan Keamanan
- Pastikan folder `storage` dan `bootstrap/cache` memiliki izin tulis (writeable).
- File `.env` sudah masuk dalam `.gitignore` untuk mencegah kebocoran kredensial.

## 🤝 Kontribusi
Project ini dikembangkan untuk kebutuhan internal Tanjungpura Network. Hubungi tim IT untuk kredensial server ACS.

---
*Dikembangkan dengan ❤️ untuk Parameter HelpDesk.*
