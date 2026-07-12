<p align="center">
  <img src="public/favicon.ico" width="80" alt="PT. Unggul Cipta Indah Logo">
</p>

<h1 align="center">PT. Unggul Cipta Indah - Company Profile & Outsourcing Portal</h1>

<p align="center">
  Platform profil perusahaan dan portal lowongan pekerjaan untuk <strong>PT. Unggul Cipta Indah</strong>, penyedia layanan outsourcing dan facility management terkemuka yang telah berdiri sejak 1994.
</p>

## ✨ Fitur Utama
- **Modern & Responsive UI**: Menggunakan Tailwind CSS untuk tampilan antarmuka yang sangat premium, *glassmorphism*, dan responsif di semua perangkat.
- **Component-Based Architecture**: Kode antarmuka (UI) modular menggunakan komponen Blade Laravel agar mudah dirawat dan dikembangkan.
- **Portal Lowongan Kerja (Job Portal)**: Sistem terintegrasi untuk menampilkan lowongan pekerjaan yang tersedia secara dinamis.
- **Dynamic Animations**: Dilengkapi dengan animasi *infinite marquee* untuk mitra strategis dan *hover states* interaktif.

## 💻 Tech Stack
- **Backend Framework**: [Laravel 11.x](https://laravel.com)
- **Frontend Styling**: [Tailwind CSS 3.x](https://tailwindcss.com)
- **Tooling**: Vite (NPM)

---

## 🚀 Panduan Instalasi (Getting Started)

Ikuti langkah-langkah di bawah ini untuk menjalankan proyek ini di *local environment* komputer Anda.

### 1. Prasyarat (Prerequisites)
Pastikan Anda sudah menginstal perangkat lunak berikut di komputer Anda:
- [PHP](https://www.php.net/downloads) (Minimal versi 8.2)
- [Composer](https://getcomposer.org/download/)
- [Node.js & npm](https://nodejs.org/en/download/)
- [Git](https://git-scm.com/downloads)

### 2. Clone Repository
Buka terminal (Command Prompt, PowerShell, atau Git Bash) dan jalankan perintah berikut untuk mengunduh kode sumber:
```bash
git clone https://github.com/ISHASII/Outsourcing-Company.git
cd Outsourcing-Company
```

### 3. Install Dependensi PHP (Composer)
Instal semua package PHP yang dibutuhkan oleh framework Laravel:
```bash
composer install
```

### 4. Install Dependensi Frontend (NPM)
Instal library frontend seperti Tailwind CSS dan Vite:
```bash
npm install
```

### 5. Setup Environment File
Salin file `.env.example` menjadi `.env`:
```bash
# Untuk pengguna Windows
copy .env.example .env

# Untuk pengguna Mac/Linux
cp .env.example .env
```

### 6. Generate Application Key
Buat kunci aplikasi Laravel yang unik (dibutuhkan untuk keamanan session dan enkripsi):
```bash
php artisan key:generate
```

### 7. Konfigurasi Database (Opsional untuk saat ini)
Secara default, Laravel menggunakan SQLite. Jika Anda ingin menggunakan database MySQL:
1. Buka file `.env`.
2. Ubah `DB_CONNECTION=sqlite` menjadi `DB_CONNECTION=mysql`.
3. Sesuaikan `DB_HOST`, `DB_PORT`, `DB_DATABASE`, `DB_USERNAME`, dan `DB_PASSWORD`.
4. Jalankan perintah migrasi:
   ```bash
   php artisan migrate --seed
   ```

### 8. Jalankan Aplikasi
Karena proyek ini menggunakan Vite untuk *asset bundling*, Anda harus menjalankan dua perintah terminal secara bersamaan (buka 2 tab terminal).

**Terminal 1 (Menjalankan server PHP Laravel):**
```bash
php artisan serve
```

**Terminal 2 (Menjalankan server Vite/Tailwind untuk live-reload):**
```bash
npm run dev
```

### 9. Akses Aplikasi
Buka browser favorit Anda dan akses URL berikut:
👉 **[http://localhost:8000](http://localhost:8000)**

---

## 📂 Struktur Direktori UI

Jika Anda ingin memodifikasi tampilan *Landing Page*, file utamanya berada di `resources/views/landingpage.blade.php`. Seluruh komponen pendukung *Landing Page* (Header, Hero, Footer, dll) telah diekstrak dan dapat ditemukan di dalam direktori komponen:
```text
resources/
└── views/
    └── components/
        └── landing/
            ├── header.blade.php
            ├── hero.blade.php
            ├── stats.blade.php
            ├── about.blade.php
            ├── vision-mission.blade.php
            ├── services.blade.php
            ├── partners.blade.php
            ├── workflow.blade.php
            ├── jobs.blade.php
            ├── cta.blade.php
            └── footer.blade.php
```

---

## 🛠️ Deployment (Production)
Jika Anda ingin mendepoloy/mengunggah aplikasi ini ke server produksi (hosting), pastikan untuk mem-build *assets* frontend terlebih dahulu:
```bash
npm run build
```
Lalu pastikan konfigurasi pada `.env` server Anda:
```env
APP_ENV=production
APP_DEBUG=false
```

<br/>
<p align="center">
  Dibuat dengan ❤️ untuk PT. Unggul Cipta Indah.
</p>
