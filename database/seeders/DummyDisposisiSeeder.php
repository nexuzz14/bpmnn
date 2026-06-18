<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SuratMasuk;
use App\Models\Disposisi;
use Carbon\Carbon;

class DummyDisposisiSeeder extends Seeder
{
    public function run()
    {
        $kasubtimId = 4;
        $stafId = 5;

        // Dummy 1: Permintaan Data BMN (Menunggu)
        $sm1 = SuratMasuk::create([
            'nomor_surat' => 'B-125/BMN/2026',
            'asal_surat' => 'Ditjen Pendis Kemenag',
            'perihal' => 'Permintaan Data BMN Semester I',
            'tanggal_surat' => Carbon::now()->subDays(2),
            'tanggal_terima' => Carbon::now()->subDays(1),
            'jenis_surat' => 'digital',
            'file_surat' => 'dummy.pdf',
            'status' => 'diproses',
            'created_by' => 1
        ]);

        Disposisi::create([
            'surat_masuk_id' => $sm1->id,
            'dari_user_id' => $kasubtimId,
            'ke_user_id' => $stafId,
            'instruksi' => 'Tindak lanjuti',
            'catatan' => 'Susun surat balasan atas permintaan data BMN Semester I dari Ditjen Pendis Kemenag. Mohon teliti datanya.',
            'tenggat_waktu' => Carbon::now()->addDays(3),
            'status' => 'menunggu'
        ]);

        // Dummy 2: Undangan Rapat (Diproses)
        $sm2 = SuratMasuk::create([
            'nomor_surat' => 'UND-458/SETJEN/2026',
            'asal_surat' => 'Sekretariat Jenderal',
            'perihal' => 'Undangan Rapat Koordinasi Anggaran',
            'tanggal_surat' => Carbon::now()->subDays(3),
            'tanggal_terima' => Carbon::now()->subDays(2),
            'jenis_surat' => 'digital',
            'file_surat' => 'dummy.pdf',
            'status' => 'diproses',
            'created_by' => 1
        ]);

        Disposisi::create([
            'surat_masuk_id' => $sm2->id,
            'dari_user_id' => $kasubtimId,
            'ke_user_id' => $stafId,
            'instruksi' => 'Siapkan Draft',
            'catatan' => 'Siapkan nota dinas untuk menugaskan tim anggaran hadir di rapat ini.',
            'tenggat_waktu' => Carbon::now()->addDays(5),
            'status' => 'diproses' // Berpura-pura staf sudah klik 'Baca'
        ]);
        
        echo "Berhasil membuat 2 tugas disposisi dummy untuk staf!\n";
    }
}
