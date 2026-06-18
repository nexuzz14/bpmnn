# Final Polish & UI Completeness Plan

Merespons permintaan untuk memverifikasi fitur PDF, Notifikasi, Laporan/Rekap, dan kelengkapan UI secara keseluruhan, berikut adalah rencana implementasi untuk melengkapi SiPersurat agar sama persis dengan desain di folder `ui/`.

## Open Questions
- Untuk fitur "Unduh Agenda (Excel)" pada Buku Agenda TU, apakah cukup menggunakan format CSV sederhana sebagai *fallback* jika kita tidak ingin menginstall package Excel berat seperti Laravel Excel (Maatwebsite)? 
- Grafik Rekap menggunakan pustaka pihak ketiga. Apakah diizinkan menggunakan **Chart.js** via CDN untuk merender grafik batang (Bar Chart) di halaman Laporan/Rekap?

## Proposed Changes

### 1. Database Notifications
- Menerapkan fitur built-in database notifications Laravel.
- Mendaftarkan notifikasi pada Observer yang sudah ada untuk mengirim *alert* ke user tujuan yang tepat.
- Menambahkan ikon lonceng dropdown pada navigasi atas (`app.blade.php`).

#### [NEW] [CreateNotificationsTable](file:///c:/laragon/www/bpmn-app/database/migrations/0000_00_00_000000_create_notifications_table.php)
#### [NEW] [SuratNotification](file:///c:/laragon/www/bpmn-app/app/Notifications/SuratNotification.php)
#### [MODIFY] [navigation.blade.php](file:///c:/laragon/www/bpmn-app/resources/views/layouts/navigation.blade.php)
#### [MODIFY] [AppServiceProvider.php](file:///c:/laragon/www/bpmn-app/app/Providers/AppServiceProvider.php) (Update Observers to dispatch notifications)

---
### 2. PDF Export Lembar Disposisi (DomPDF)
- Menggunakan `barryvdh/laravel-dompdf` untuk mengekspor Lembar Disposisi menjadi PDF.

#### [NEW] [pdf.blade.php](file:///c:/laragon/www/bpmn-app/resources/views/tu/disposisi/pdf.blade.php)
#### [MODIFY] [TU\DisposisiController.php](file:///c:/laragon/www/bpmn-app/app/Http/Controllers/TU/DisposisiController.php) (Add `pdf` method)
#### [MODIFY] [web.php](file:///c:/laragon/www/bpmn-app/routes/web.php) (Add `GET /disposisi/{id}/pdf`)

---
### 3. Perbaikan Flow TTD (Upload TTD Fisik oleh TU)
- Di UI, Kepala Biro tidak langsung menerbitkan *Surat Final*. Kabiro hanya *menyetujui* draf.
- Setelah disetujui, TU Biro bertugas mencetak, meminta TTD basah, lalu mengunggahnya (Upload TTD) ke dalam sistem sebagai `SuratFinal`.

#### [MODIFY] [Kabiro\ReviewFinalController.php](file:///c:/laragon/www/bpmn-app/app/Http/Controllers/Kabiro/ReviewFinalController.php) (Change logic to only update status to `menunggu_ttd`)
#### [NEW] [TU\UploadTtdController.php](file:///c:/laragon/www/bpmn-app/app/Http/Controllers/TU/UploadTtdController.php) (List and store physical scanned PDF)
#### [NEW] [tu/upload-ttd/index.blade.php](file:///c:/laragon/www/bpmn-app/resources/views/tu/upload-ttd/index.blade.php)
#### [NEW] [tu/upload-ttd/create.blade.php](file:///c:/laragon/www/bpmn-app/resources/views/tu/upload-ttd/create.blade.php)

---
### 4. Menu Progress Surat & Riwayat (Seluruh Role)
- Menambahkan menu `Progress Surat` (melacak status reviu dan disposisi sebuah surat masuk/draf secara *timeline* visual).

#### [NEW] [ProgressSuratController.php](file:///c:/laragon/www/bpmn-app/app/Http/Controllers/ProgressSuratController.php) (Reusable controller for all roles)
#### [NEW] [shared/progress-surat/index.blade.php](file:///c:/laragon/www/bpmn-app/resources/views/shared/progress-surat/index.blade.php)
#### [NEW] [shared/progress-surat/show.blade.php](file:///c:/laragon/www/bpmn-app/resources/views/shared/progress-surat/show.blade.php)
#### [MODIFY] [web.php](file:///c:/laragon/www/bpmn-app/routes/web.php) (Add routes for all roles)

---
### 5. Halaman Laporan & Rekap (UI Completeness)
- Admin: Tambah `Rekap Surat` dan `Arsip`
- TU: Tambah `Buku Agenda`
- Kabag: Tambah `Rekap Bagian`
- Kabiro: Tambah `Rekap Bulanan`

#### [NEW] [Admin\LaporanController.php](file:///c:/laragon/www/bpmn-app/app/Http/Controllers/Admin/LaporanController.php)
#### [NEW] [TU\AgendaController.php](file:///c:/laragon/www/bpmn-app/app/Http/Controllers/TU/AgendaController.php)
#### [NEW] [Kabag\RekapController.php](file:///c:/laragon/www/bpmn-app/app/Http/Controllers/Kabag/RekapController.php)
#### [NEW] [Kabiro\RekapController.php](file:///c:/laragon/www/bpmn-app/app/Http/Controllers/Kabiro/RekapController.php)
#### [NEW] Views untuk setiap rekap (menggunakan Chart.js untuk chart batang/bulanan).

## Verification Plan
1. Membuat disposisi baru dan memastikan notifikasi *database* muncul di akun penerima.
2. Membuka detail disposisi dan mengklik tombol "Cetak PDF" untuk memverifikasi fungsionalitas DomPDF.
3. Kabiro menyetujui draf, kemudian memeriksa menu "Upload TTD" di akun TU Biro.
4. Meninjau menu Rekap/Laporan untuk setiap role untuk memastikan grafik Chart.js tampil dengan data statis/dinamis yang valid.
