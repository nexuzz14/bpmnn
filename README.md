# SiPersurat - Sistem Informasi Persuratan Kementerian Agama RI

SiPersurat adalah aplikasi manajemen persuratan, disposisi, dan tata naskah dinas elektronik yang dikembangkan khusus untuk Biro Keuangan & BMN, Sekretariat Jenderal Kementerian Agama RI.

---

## 💻 Persyaratan Sistem (System Requirements)

Pastikan server atau komputer Anda memenuhi spesifikasi berikut sebelum melakukan instalasi:
- **PHP** versi 8.2 atau lebih baru
- **Composer** (untuk manajemen paket PHP)
- **Node.js** & **npm** (untuk *build assets* - *Opsional jika folder public/build sudah ada*)
- **Database** MySQL / MariaDB
- Web Server (Apache/Nginx) atau menggunakan environment lokal seperti Laragon / XAMPP

## 🚀 Panduan Instalasi (Installation Guide)

Ikuti langkah-langkah berikut untuk menjalankan aplikasi di *server* / komputer lokal Anda:

### 1. Ekstrak Folder
Ekstrak (Unzip) file *source code* ini ke dalam folder *document root* web server Anda (contoh: `C:\laragon\www\sipersurat` atau `C:\xampp\htdocs\sipersurat`).

### 2. Instalasi Dependensi PHP
Buka Terminal / Command Prompt / Git Bash, arahkan ke folder proyek, lalu jalankan:
```bash
composer install
```

### 3. Konfigurasi Environment (`.env`)
1. *Copy* file `.env.example` dan ubah namanya menjadi `.env`:
   ```bash
   cp .env.example .env
   ```
2. Buka file `.env` yang baru dibuat dengan *text editor* (Notepad/VSCode).
3. Sesuaikan konfigurasi *database* dengan database MySQL Anda:
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=nama_database_anda
   DB_USERNAME=root
   DB_PASSWORD=
   ```

### 4. Generate Application Key
Jalankan perintah berikut untuk meng-generate *APP_KEY* keamanan Laravel:
```bash
php artisan key:generate
```

### 5. Setup Database (Pilih Salah Satu)

**Opsi A: Import File SQL (Direkomendasikan)**
Bagi Anda yang menggunakan *phpMyAdmin* (misalnya di XAMPP / Laragon):
1. Buka phpMyAdmin di browser Anda (biasanya `http://localhost/phpmyadmin`).
2. Buat database baru yang kosong (contoh nama: `bpmn_app` atau sesuai `.env`).
3. Masuk ke database tersebut, lalu pilih tab **Import**.
4. Pilih file **`bpmn_app.sql`** yang tersedia di dalam folder ini, kemudian klik **Import** / **Go**.

**Opsi B: Menggunakan Fitur Artisan (Terminal)**
Jika Anda terbiasa menggunakan *command-line*, jalankan:
```bash
php artisan migrate:fresh --seed
```
*Catatan: Perintah ini otomatis membuat struktur tabel dan mengisi akun default.*

### 6. Hubungkan Storage Dokumen (Symlink)
Sistem membutuhkan *symlink* agar file *upload* surat dan draf dapat diakses oleh publik:
```bash
php artisan storage:link
```

### 7. Jalankan Aplikasi
Jika menggunakan Laragon/XAMPP, Anda bisa langsung mengakses URL lokal proyek Anda (misal: `http://sipersurat.test`). 
Jika Anda ingin menggunakan *built-in server* PHP, jalankan:
```bash
php artisan serve
```
Aplikasi kini dapat diakses di **http://localhost:8000**

---

## 🔑 Akses Akun Pengguna (Default Accounts)

Setelah melakukan instalasi dan *seeding*, Anda dapat login menggunakan akun berikut:

| Peran (Role) | Email | Password |
| --- | --- | --- |
| **Admin** | admin@bkbmn.go.id | password |
| **Tata Usaha (TU)** | tu@bkbmn.go.id | password |
| **Ketua/Kasubtim** | kasubtim@bkbmn.go.id | password |
| **Staf** | staf@bkbmn.go.id | password |

---

## 🎨 Pengembangan Frontend (Opsional)
Aplikasi ini menggunakan **Tailwind CSS**. Jika Anda melakukan perubahan pada desain atau file `.blade.php`, Anda perlu me- *rebuild assets* dengan cara:
```bash
npm install
npm run build
```

## 📞 Bantuan & Dukungan (Support)
Jika Kakak mengalami kendala atau kesulitan saat proses instalasi, jangan ragu untuk menghubungi kami. Nanti akan langsung kami bantu proses instalasinya melalui remote menggunakan **AnyDesk**.

---
*Dikembangkan untuk Kementerian Agama RI © 2026*
