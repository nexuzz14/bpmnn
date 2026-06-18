<?php
$controllers = [
    'Admin' => 'admin',
    'Tu' => 'tata_usaha',
    'Kabag' => 'kepala_bagian',
    'Kasubtim' => 'kepala_sub_tim',
    'Staf' => 'staf',
    'Kabiro' => 'kepala_biro'
];

foreach ($controllers as $namespace => $role) {
    $dir = "c:/laragon/www/bpmn-app/app/Http/Controllers/{$namespace}";
    if (!is_dir($dir)) {
        mkdir($dir, 0777, true);
    }
    
    $content = file_get_contents("c:/laragon/www/bpmn-app/app/Http/Controllers/ProgressController.php");
    $content = str_replace("namespace App\Http\Controllers;", "namespace App\Http\Controllers\\{$namespace};", $content);
    
    // Add show method
    $showMethod = "
    public function show(\$id)
    {
        \$drafSurat = DrafSurat::with(['suratMasuk', 'pembuat.unitKerja', 'reviuSurat.user.unitKerja', 'suratFinal', 'reviuSurat' => function(\$q) {
            \$q->orderBy('created_at', 'asc');
        }])->findOrFail(\$id);
        
        return view('progress.show', compact('drafSurat'));
    }
}
";
    $content = preg_replace("/\}\s*$/", $showMethod, $content);
    
    file_put_contents("$dir/ProgressController.php", $content);
}
