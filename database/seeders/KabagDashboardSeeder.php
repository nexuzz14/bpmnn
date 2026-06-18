<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class KabagDashboardSeeder extends Seeder
{
    public function run()
    {
        $kabag = \App\Models\User::where('role', 'kepala_bagian')->first();
        $kasubtim = \App\Models\User::where('role', 'kepala_sub_tim')->first();
        $staf = \App\Models\User::where('role', 'staf')->first();

        if (!$kabag) { echo "No Kabag found"; exit; }

        DB::table('surat_masuks')->where('nomor_surat', 'like', '#2025%')->delete();

        // Dummy Surat Masuk 1
        $sm1 = clone \App\Models\SuratMasuk::first() ?? new \App\Models\SuratMasuk();
        $sm1->nomor_surat = '#2025/125';
        $sm1->asal_surat = 'Kemenkeu';
        $sm1->perihal = 'Permintaan Data BMN Semester I';
        $sm1->tanggal_surat = '2026-04-20';
        $sm1->tanggal_terima = '2026-04-21';
        $sm1->file_surat = 'dummy.pdf';
        $sm1->save();

        // Disposisi 1
        \App\Models\Disposisi::create([
            'surat_masuk_id' => $sm1->id,
            'dari_user_id' => $staf->id ?? 1,
            'ke_user_id' => $kabag->id,
            'instruksi' => 'Tindak lanjuti',
            'catatan' => 'Mohon ditindaklanjuti dan disposisikan ke Kasubtim terkait untuk penyusunan data BMN semester I.',
            'tenggat_waktu' => '2026-04-30 23:59:59',
            'status' => 'menunggu'
        ]);

        // Dummy Surat Masuk 2
        $sm2 = clone \App\Models\SuratMasuk::first() ?? new \App\Models\SuratMasuk();
        $sm2->nomor_surat = '#2025/120';
        $sm2->asal_surat = 'Bappenas';
        $sm2->perihal = 'Laporan Keuangan Triwulan I';
        $sm2->tanggal_surat = '2026-04-18';
        $sm2->tanggal_terima = '2026-04-19';
        $sm2->file_surat = 'dummy2.pdf';
        $sm2->save();

        // Disposisi 2
        \App\Models\Disposisi::create([
            'surat_masuk_id' => $sm2->id,
            'dari_user_id' => $staf->id ?? 1,
            'ke_user_id' => $kabag->id,
            'instruksi' => 'Koordinasikan',
            'catatan' => 'Koordinasikan dengan tim untuk finalisasi laporan keuangan triwulan pertama.',
            'tenggat_waktu' => '2026-04-28 23:59:59',
            'status' => 'diproses'
        ]);

        // Dummy Surat Masuk 3 for Draf
        $sm3 = clone \App\Models\SuratMasuk::first() ?? new \App\Models\SuratMasuk();
        $sm3->nomor_surat = '#2025/110';
        $sm3->asal_surat = 'Internal';
        $sm3->perihal = 'Balasan Permintaan Data BMN Semester I';
        $sm3->tanggal_surat = '2026-04-15';
        $sm3->tanggal_terima = '2026-04-15';
        $sm3->file_surat = 'dummy3.pdf';
        $sm3->save();

        // Draf Surat 1
        $draf = \App\Models\DrafSurat::create([
            'surat_masuk_id' => $sm3->id,
            'dibuat_oleh' => $kasubtim->id ?? 2,
            'file_draf' => 'draf.pdf',
            'status' => 'revisi_kasubtim'
        ]);

        // Reviu Surat (Menunggu Kabag)
        \App\Models\ReviuSurat::create([
            'draf_surat_id' => $draf->id,
            'reviewer_id' => $kabag->id,
            'tingkat' => 2,
            'status' => 'menunggu'
        ]);

        // Add 12 dummy reviews approved this month
        for($i=0; $i<12; $i++) {
            \App\Models\ReviuSurat::create([
                'draf_surat_id' => $draf->id,
                'reviewer_id' => $kabag->id,
                'tingkat' => 2,
                'status' => 'disetujui'
            ]);
        }

        echo "Seeded kabag data successfully.\n";
    }
}