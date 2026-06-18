<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\SuratMasuk;
use App\Models\Disposisi;
use App\Models\DrafSurat;
use App\Models\ReviuSurat;
use Illuminate\Support\Str;

class DraftDataSeeder extends Seeder
{
    public function run()
    {
        $kasubtim = User::where('role', 'kepala_sub_tim')->first();
        $staf = User::where('role', 'staf')->first();
        $kabag = User::where('role', 'kepala_bagian')->first();
        $tu = User::where('role', 'tata_usaha')->first();

        if (!$kasubtim || !$staf || !$kabag || !$tu) {
            $this->command->info('Users not found.');
            return;
        }

        // 1. Data untuk "Perlu Review"
        $surat1 = SuratMasuk::updateOrCreate(
            ['nomor_surat' => 'B-125-REV2/Dt.I.II/KU.00/07/2025'],
            [
                'tanggal_surat' => '2025-07-08',
                'tanggal_terima' => '2025-07-10',
                'asal_surat' => 'Ditjen Pendis Kemenag',
                'perihal' => 'Balasan Permintaan Data BMN Semester I',
                'file_surat' => 'dummy.pdf',
                'created_by' => $tu->id,
            ]
        );

        // Disposisi dari Kabag ke Kasubtim
        Disposisi::create([
            'surat_masuk_id' => $surat1->id,
            'dari_user_id' => $kabag->id,
            'ke_user_id' => $kasubtim->id,
            'catatan' => 'Tolong segera disiapkan datanya.',
            'instruksi' => 'Tolong segera disiapkan datanya.',
            'tenggat_waktu' => '2025-07-15',
            'status' => 'ditindaklanjuti',
        ]);

        // Penugasan dari Kasubtim ke Staf
        Disposisi::create([
            'surat_masuk_id' => $surat1->id,
            'dari_user_id' => $kasubtim->id,
            'ke_user_id' => $staf->id,
            'catatan' => '1. Menyiapkan rekap data BMN semester I TA 2025 Ditjen Pendis
2. Melampirkan data aset tetap dan persediaan terkini
3. Surat balasan dikirim paling lambat 17 Juli 2025',
            'instruksi' => '1. Menyiapkan rekap data BMN semester I TA 2025 Ditjen Pendis
2. Melampirkan data aset tetap dan persediaan terkini
3. Surat balasan dikirim paling lambat 17 Juli 2025',
            'tenggat_waktu' => '2025-07-14',
            'status' => 'ditindaklanjuti',
        ]);

        // Draft dibuat Staf
        $draf1 = DrafSurat::create([
            'surat_masuk_id' => $surat1->id,
            'dibuat_oleh' => $staf->id,
            'file_draf' => 'dummy_draf.pdf',
            'keterangan' => 'Berikut draft balasan beserta rekap datanya pak.',
            'versi' => 1,
            'status' => 'menunggu_reviu',
            'created_at' => '2026-04-26 14:30:00'
        ]);

        // Review Menunggu Kasubtim
        ReviuSurat::create([
            'draf_surat_id' => $draf1->id,
            'reviewer_id' => $kasubtim->id,
            'tingkat' => '1',
            'status' => 'menunggu',
            'created_at' => '2026-04-26 14:30:00'
        ]);

        // 2. Data untuk "Sudah Diproses" - Diteruskan ke Kabag
        $surat2 = SuratMasuk::updateOrCreate(
            ['nomor_surat' => 'B-115-REV2/Dt.I.II/KU.00/07/2025'],
            [
                'tanggal_surat' => '2025-07-05',
                'tanggal_terima' => '2025-07-08',
                'asal_surat' => 'Kanwil Kemenag Jatim',
                'perihal' => 'Usulan Pengadaan Aset TIK',
                'file_surat' => 'dummy.pdf',
                'created_by' => $tu->id,
            ]
        );

        Disposisi::create([
            'surat_masuk_id' => $surat2->id,
            'dari_user_id' => $kasubtim->id,
            'ke_user_id' => $staf->id,
            'catatan' => 'Cek kelengkapan usulan TIK ini.',
            'instruksi' => 'Cek kelengkapan usulan TIK ini.',
            'tenggat_waktu' => '2025-07-12',
            'status' => 'ditindaklanjuti',
        ]);

        $draf2 = DrafSurat::create([
            'surat_masuk_id' => $surat2->id,
            'dibuat_oleh' => $staf->id,
            'file_draf' => 'dummy_draf2.pdf',
            'versi' => 1,
            'status' => 'sedang_direviu',
            'created_at' => '2026-04-24 10:20:00'
        ]);

        ReviuSurat::create([
            'draf_surat_id' => $draf2->id,
            'reviewer_id' => $kasubtim->id,
            'tingkat' => '1',
            'status' => 'disetujui',
            'catatan_reviu' => 'Oke, sudah sesuai. Lanjut ke Kabag.',
            'created_at' => '2026-04-24 11:00:00'
        ]);

        // Menunggu Kabag
        ReviuSurat::create([
            'draf_surat_id' => $draf2->id,
            'reviewer_id' => $kabag->id,
            'tingkat' => '2',
            'status' => 'menunggu',
        ]);

        // 3. Data untuk "Sudah Diproses" - Revisi (Dikembalikan ke Staf)
        $surat3 = SuratMasuk::updateOrCreate(
            ['nomor_surat' => 'B-110-REV2/Dt.I.II/KU.00/07/2025'],
            [
                'tanggal_surat' => '2025-07-02',
                'tanggal_terima' => '2025-07-05',
                'asal_surat' => 'Ditjen Bimas Islam',
                'perihal' => 'Laporan Monitoring BMN Triwulan I',
                'file_surat' => 'dummy.pdf',
                'created_by' => $tu->id,
            ]
        );

        Disposisi::create([
            'surat_masuk_id' => $surat3->id,
            'dari_user_id' => $kasubtim->id,
            'ke_user_id' => $staf->id,
            'catatan' => 'Buat draf laporannya',
            'instruksi' => 'Buat draf laporannya',
            'tenggat_waktu' => '2025-07-10',
            'status' => 'ditindaklanjuti',
        ]);

        $draf3 = DrafSurat::create([
            'surat_masuk_id' => $surat3->id,
            'dibuat_oleh' => $staf->id,
            'file_draf' => 'dummy_draf3.pdf',
            'versi' => 3,
            'status' => 'revisi',
            'created_at' => '2026-04-22 09:15:00'
        ]);

        ReviuSurat::create([
            'draf_surat_id' => $draf3->id,
            'reviewer_id' => $kasubtim->id,
            'tingkat' => '1',
            'status' => 'revisi',
            'catatan_reviu' => 'Tabel di halaman 3 tolong formatnya diperbaiki.',
            'created_at' => '2026-04-22 13:00:00'
        ]);

        // 4. Data untuk "Sudah Diproses" - Selesai
        $surat4 = SuratMasuk::updateOrCreate(
            ['nomor_surat' => 'B-105-REV2/Dt.I.II/KU.00/07/2025'],
            [
                'tanggal_surat' => '2025-06-25',
                'tanggal_terima' => '2025-07-01',
                'asal_surat' => 'Inspektorat Jenderal',
                'perihal' => 'Surat Balasan Permohonan Data',
                'file_surat' => 'dummy.pdf',
                'created_by' => $tu->id,
            ]
        );

        Disposisi::create([
            'surat_masuk_id' => $surat4->id,
            'dari_user_id' => $kasubtim->id,
            'ke_user_id' => $staf->id,
            'instruksi' => '',
            'status' => 'ditindaklanjuti',
        ]);

        $draf4 = DrafSurat::create([
            'surat_masuk_id' => $surat4->id,
            'dibuat_oleh' => $staf->id,
            'file_draf' => 'dummy_draf4.pdf',
            'versi' => 1,
            'status' => 'selesai',
            'created_at' => '2026-04-20 15:30:00'
        ]);

        ReviuSurat::create([
            'draf_surat_id' => $draf4->id,
            'reviewer_id' => $kasubtim->id,
            'tingkat' => '1',
            'status' => 'disetujui',
            'created_at' => '2026-04-20 16:00:00'
        ]);
        $kabiro = User::where('role', 'kepala_biro')->first();
        ReviuSurat::create([
            'draf_surat_id' => $draf4->id,
            'reviewer_id' => $kabag->id,
            'tingkat' => '2',
            'status' => 'disetujui',
            'created_at' => '2026-04-21 10:00:00'
        ]);
        ReviuSurat::create([
            'draf_surat_id' => $draf4->id,
            'reviewer_id' => $kabiro ? $kabiro->id : null,
            'tingkat' => '3',
            'status' => 'disetujui',
            'created_at' => '2026-04-21 14:00:00'
        ]);

        $this->command->info('Data draft surat berhasil disemai.');
    }
}
