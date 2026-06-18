<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$response = $kernel->handle(
    $request = Illuminate\Http\Request::capture()
);

$draf = App\Models\DrafSurat::create([
    'surat_masuk_id' => 1,
    'dibuat_oleh' => 5,
    'file_draf' => 'dummy.pdf',
    'status' => 'menunggu_ttd'
]);
App\Models\ReviuSurat::create([
    'draf_surat_id' => $draf->id,
    'reviewer_id' => 6, // Kabiro
    'status' => 'disetujui',
    'catatan_reviu' => 'Silahkan didistribusikan',
    'tingkat' => 'final'
]);
echo "Created dummy DrafSurat with ID: " . $draf->id . "\n";
