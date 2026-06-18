<?php
$dir = new RecursiveDirectoryIterator('c:/laragon/www/bpmn-app/resources/views');
$iterator = new RecursiveIteratorIterator($dir);
$files = new RegexIterator($iterator, '/^.+\.blade\.php$/i', RecursiveRegexIterator::GET_MATCH);

$replacements = [
    // Dummy strings
    "'Permintaan Data BMN Semester I Tahun Anggaran 2025'" => "'-'",
    "'Balasan Permintaan Data BMN Semester I'" => "'-'",
    "'Balasan Permintaan Data BMN'" => "'-'",
    "'B-125/Dt.I.II/KU.00/07/2025'" => "'-'",
    "'#2025/125'" => "'-'",
    "'#2025/105'" => "'-'",
    "'#2025/098'" => "'-'",
    "'#2025/' . rand(100, 999)" => "'-'",
    "'2026/000'" => "'-'",
    "'Ditjen Pendidikan Islam Kemenag meminta Biro Keuangan dan BMN untuk menyediakan data rekap Barang Milik Negara (BMN) semester I tahun anggaran 2025 yang berada di bawah pengelolaan Ditjen Pendis...'" => "'-'",
    "Balasan atas permintaan data BMN Semester I dari Ditjen Pendis Kemenag" => "-",
    "Usulan Revisi Anggaran Q2 Tahun 2025" => "-",
    "Laporan Kinerja Bagian Keuangan Semester I 2025" => "-",
    "Penyampaian Data Dukung Pembayaran Tunjangan Kinerja Bulan Juni 2025" => "-",
    "Permintaan Data BMN Semester I Tahun Anggaran 2025" => "-",
    "Menyiapkan rekap data BMN semester I TA 2026 Ditjen Pendis" => "-",
    
    // href="#" in actions
    '<a href="#" class="inline-flex px-4 py-2 border border-[#0284c7] text-[#0284c7] font-medium rounded text-xs hover:bg-blue-50 transition-colors shadow-sm bg-white">' => '<a href="{{ route(\'tu.surat-final.show\', $surat) ?? \'#\' }}" class="inline-flex px-4 py-2 border border-[#0284c7] text-[#0284c7] font-medium rounded text-xs hover:bg-blue-50 transition-colors shadow-sm bg-white">',
];

$count = 0;
foreach ($files as $file) {
    $path = $file[0];
    // Exclude kabag as it's already done, but no harm if we run it anyway
    $content = file_get_contents($path);
    $newContent = str_replace(array_keys($replacements), array_values($replacements), $content);
    
    // Custom regex replacements for href="#"
    $newContent = preg_replace('/href="#"\s*(class="[^"]*text-gray-500[^"]*Lihat[^"]*")/', 'href="{{ url()->current() }}" $1', $newContent);
    $newContent = preg_replace('/<a href="#"\s+class="([^"]*)"[^>]*>\s*Lihat\s*<\/a>/i', '<a href="{{ url()->current() }}" class="$1">Lihat</a>', $newContent);

    if ($content !== $newContent) {
        file_put_contents($path, $newContent);
        echo "Updated: $path\n";
        $count++;
    }
}
echo "Total files updated: $count\n";
