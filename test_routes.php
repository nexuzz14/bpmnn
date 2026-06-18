<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

$_SERVER['SERVER_NAME'] = 'localhost'; // Fix Invalid URI
$_SERVER['REMOTE_ADDR'] = '127.0.0.1'; // Fix null IP error in HttpFoundation

$response = $kernel->handle(
    $request = Illuminate\Http\Request::capture()
);

$users = App\Models\User::all()->groupBy('role');
$testRoles = [
    'admin' => ['admin.dashboard', 'admin.users.index', 'admin.rekap.index'],
    'tata_usaha' => ['tu.dashboard', 'tu.surat-masuk.index', 'tu.disposisi.index', 'tu.progress.index', 'tu.buku-agenda.index', 'tu.surat-final.index'],
    'kepala_bagian' => ['kabag.dashboard', 'kabag.review.index', 'kabag.progress.index', 'kabag.terdistribusi.index', 'kabag.rekap.index'],
    'kepala_sub_tim' => ['kasubtim.dashboard', 'kasubtim.penugasan.index', 'kasubtim.draft.index', 'kasubtim.review.index', 'kasubtim.riwayat.index', 'kasubtim.progress.index'],
    'staf' => ['staf.dashboard', 'staf.tugas.index', 'staf.buat-surat.index', 'staf.draf-surat.index', 'staf.progress.index', 'staf.selesai.index'],
    'kepala_biro' => ['kabiro.dashboard', 'kabiro.review-final.index', 'kabiro.progress.index', 'kabiro.terdistribusi.index', 'kabiro.rekap.index']
];
$hasError = false;

foreach ($testRoles as $role => $routes) {
    $user = $users[$role]->first() ?? null;
    if (!$user) {
        echo "No user found for role: $role\n";
        continue;
    }
    
    echo "\n=== Testing Role: $role (User ID: {$user->id}) ===\n";
    Auth::login($user);
    
    foreach ($routes as $routeName) {
        try {
            $url = route($routeName);
            $req = Illuminate\Http\Request::create($url, 'GET');
            $req->setLaravelSession($request->session());
            $res = $kernel->handle($req);
            
            if ($res->getStatusCode() === 200) {
                echo "[\033[32mOK\033[0m] $routeName\n";
            } else {
                echo "[\033[31mFAIL\033[0m] $routeName returned status " . $res->getStatusCode() . "\n";
                if ($res->getStatusCode() == 500) {
                    echo substr(strip_tags($res->getContent()), 0, 500) . "\n";
                }
                $hasError = true;
            }
        } catch (\Exception $e) {
            echo "[\033[31mERROR\033[0m] $routeName: " . $e->getMessage() . " at " . $e->getFile() . ":" . $e->getLine() . "\n";
            $hasError = true;
        }
    }
}
