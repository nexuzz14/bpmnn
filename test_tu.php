<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$response = $kernel->handle(
    $request = Illuminate\Http\Request::capture()
);

$user = App\Models\User::where('role', 'tata_usaha')->first();
Auth::login($user);
$req = Illuminate\Http\Request::create(route('tu.dashboard'), 'GET');
$req->setLaravelSession($request->session());
$_SERVER['SERVER_NAME'] = 'localhost';
$res = $kernel->handle($req);
echo $res->getStatusCode() . "\n";
