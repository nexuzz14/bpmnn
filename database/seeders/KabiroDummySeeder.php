<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SuratMasuk;
use App\Models\Disposisi;
use App\Models\DrafSurat;
use App\Models\ReviuSurat;
use Carbon\Carbon;
use App\Models\User;
use App\Models\SuratFinal;

class KabiroDummySeeder extends Seeder
{
    public function run()
    {
        $stafId = 5;
        $kasubtimId = 4;
        $kabagId = 3;
        $kabiroId = 6;
        $tuId = 2;

        // 1. DRAFT-001/2026 - Menunggu Review Kabiro
        $sm1 = SuratMasuk::create([
            'nomor_surat' => 'B-001Z/TEST/2026',
            'asal_surat' => 'Instansi Luar 1',
            'perihal' => 'Balasan Permintaan Data BMN Semester I',
            'tanggal_surat' => Carbon::now()->subDays(10),
            'tanggal_terima' => Carbon::now()->subDays(9),
            'jenis_surat' => 'digital',
            'status' => 'diproses',
            'created_by' => $tuId
        ]);

        $draf1 = DrafSurat::create([
            'surat_masuk_id' => $sm1->id,
            'dibuat_oleh' => $stafId,
            'file_draf' => 'dummy1.pdf',
            'status' => 'menunggu_reviu',
            'created_at' => Carbon::now()->subDays(4),
            'updated_at' => Carbon::now()->subDays(4)
        ]);

        // Kasubtim Approved
        ReviuSurat::create([
            'draf_surat_id' => $draf1->id,
            'reviewer_id' => $kasubtimId,
            'tingkat' => '1',
            'status' => 'disetujui',
            'catatan_reviu' => 'Sesuai',
            'created_at' => Carbon::now()->subDays(3),
            'updated_at' => Carbon::now()->subDays(3)
        ]);
        // Kabag Approved
        ReviuSurat::create([
            'draf_surat_id' => $draf1->id,
            'reviewer_id' => $kabagId,
            'tingkat' => '2',
            'status' => 'disetujui',
            'catatan_reviu' => 'Lanjut Kabiro',
            'created_at' => Carbon::now()->subDays(2),
            'updated_at' => Carbon::now()->subDays(2)
        ]);
        // Kabiro Pending
        ReviuSurat::create([
            'draf_surat_id' => $draf1->id,
            'reviewer_id' => $kabiroId,
            'tingkat' => 'final',
            'status' => 'menunggu',
            'created_at' => Carbon::now()->subDays(1),
            'updated_at' => Carbon::now()->subDays(1)
        ]);

        // 2. DRAFT-002/2026 - Disetujui
        $sm2 = SuratMasuk::create([
            'nomor_surat' => 'B-002Z/TEST/2026',
            'asal_surat' => 'Instansi Luar 2',
            'perihal' => 'Laporan Realisasi Anggaran Q1 2026',
            'tanggal_surat' => Carbon::now()->subDays(15),
            'tanggal_terima' => Carbon::now()->subDays(14),
            'jenis_surat' => 'digital',
            'status' => 'diproses',
            'created_by' => $tuId
        ]);

        $draf2 = DrafSurat::create([
            'surat_masuk_id' => $sm2->id,
            'dibuat_oleh' => $stafId,
            'file_draf' => 'dummy2.pdf',
            'status' => 'menunggu_ttd',
            'created_at' => Carbon::now()->subDays(5),
            'updated_at' => Carbon::now()->subDays(2)
        ]);

        ReviuSurat::create([
            'draf_surat_id' => $draf2->id,
            'reviewer_id' => $kabiroId,
            'tingkat' => 'final',
            'status' => 'disetujui',
            'catatan_reviu' => 'OK',
            'created_at' => Carbon::now()->subDays(2),
            'updated_at' => Carbon::now()->subDays(2)
        ]);

        // 3. DRAFT-003/2026 - Disetujui
        $sm3 = SuratMasuk::create([
            'nomor_surat' => 'B-003Z/TEST/2026',
            'asal_surat' => 'Instansi Luar 3',
            'perihal' => 'Usulan Pengadaan Aset TIK',
            'tanggal_surat' => Carbon::now()->subDays(20),
            'tanggal_terima' => Carbon::now()->subDays(19),
            'jenis_surat' => 'digital',
            'status' => 'diproses',
            'created_by' => $tuId
        ]);

        $draf3 = DrafSurat::create([
            'surat_masuk_id' => $sm3->id,
            'dibuat_oleh' => $stafId,
            'file_draf' => 'dummy3.pdf',
            'status' => 'menunggu_ttd',
            'created_at' => Carbon::now()->subDays(6),
            'updated_at' => Carbon::now()->subDays(3)
        ]);

        ReviuSurat::create([
            'draf_surat_id' => $draf3->id,
            'reviewer_id' => $kabiroId,
            'tingkat' => 'final',
            'status' => 'disetujui',
            'catatan_reviu' => 'OK segera eksekusi',
            'created_at' => Carbon::now()->subDays(3),
            'updated_at' => Carbon::now()->subDays(3)
        ]);

        echo "Berhasil membuat 3 data dummy khusus untuk Kepala Biro!\n";
    }
}
