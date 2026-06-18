$kabag = \App\Models\User::where('role', 'kepala_bagian')->first();
$kasubtim = \App\Models\User::where('role', 'kepala_sub_tim')->first();
$staf = \App\Models\User::where('role', 'staf')->first();

if (!$kabag) { echo "No Kabag found"; exit; }

// Dummy Surat Masuk 1
$sm1 = \App\Models\SuratMasuk::create([
    'nomor_surat' => '#2025/125',
    'asal_surat' => 'Kemenkeu',
    'perihal' => 'Permintaan Data BMN Semester I',
    'tanggal_surat' => '2026-04-20',
    'tanggal_diterima' => '2026-04-21',
    'file_surat' => 'dummy.pdf',
    'status' => 'disposisi',
    'sifat' => 'biasa'
]);

// Disposisi 1
\App\Models\Disposisi::create([
    'surat_masuk_id' => $sm1->id,
    'dari_user_id' => $staf->id ?? 1,
    'ke_user_id' => $kabag->id,
    'catatan' => 'Mohon ditindaklanjuti dan disposisikan ke Kasubtim terkait untuk penyusunan data BMN semester I.',
    'tenggat_waktu' => '2026-04-30 23:59:59',
    'status' => 'menunggu',
    'is_read' => false
]);

// Dummy Surat Masuk 2
$sm2 = \App\Models\SuratMasuk::create([
    'nomor_surat' => '#2025/120',
    'asal_surat' => 'Bappenas',
    'perihal' => 'Laporan Keuangan Triwulan I',
    'tanggal_surat' => '2026-04-18',
    'tanggal_diterima' => '2026-04-19',
    'file_surat' => 'dummy2.pdf',
    'status' => 'disposisi',
    'sifat' => 'segera'
]);

// Disposisi 2
\App\Models\Disposisi::create([
    'surat_masuk_id' => $sm2->id,
    'dari_user_id' => $staf->id ?? 1,
    'ke_user_id' => $kabag->id,
    'catatan' => 'Koordinasikan dengan tim untuk finalisasi laporan keuangan triwulan pertama.',
    'tenggat_waktu' => '2026-04-28 23:59:59',
    'status' => 'diproses',
    'is_read' => true
]);

// Dummy Surat Masuk 3 for Draf
$sm3 = \App\Models\SuratMasuk::create([
    'nomor_surat' => '#2025/110',
    'asal_surat' => 'Internal',
    'perihal' => 'Balasan Permintaan Data BMN Semester I',
    'tanggal_surat' => '2026-04-15',
    'tanggal_diterima' => '2026-04-15',
    'file_surat' => 'dummy3.pdf',
    'status' => 'diproses',
    'sifat' => 'biasa'
]);

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
    'role_reviewer' => 'kepala_bagian',
    'tingkat' => 2,
    'status' => 'menunggu'
]);

// Add 12 dummy reviews approved this month
for($i=0; $i<12; $i++) {
    \App\Models\ReviuSurat::create([
        'draf_surat_id' => $draf->id,
        'reviewer_id' => $kabag->id,
        'role_reviewer' => 'kepala_bagian',
        'tingkat' => 2,
        'status' => 'disetujui'
    ]);
}

echo "Database seeded with dummy data for Kabag dashboard!";
