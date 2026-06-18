<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use App\Models\User;

class VerifyRoutes extends Command
{
    protected $signature = 'verify:routes';
    protected $description = 'Verify all sidebar routes for 6 roles';

    public function handle()
    {
        $routesByRole = [
            'admin' => [
                'admin.dashboard',
                'admin.surat-masuk.index',
                'admin.users.index',
                'admin.units.index',
                'admin.rekap.index',
                'admin.arsip.index',
            ],
            'tata_usaha' => [
                'tu.dashboard',
                'tu.surat-masuk.index',
                'tu.buku-agenda.index',
                'progress.index',
                'tu.disposisi.index',
                'tu.disposisi.riwayat',
                'tu.upload-ttd.index',
                'tu.surat-final.index',
            ],
            'kepala_bagian' => [
                'kabag.dashboard',
                'kabag.disposisi.index',
                'kabag.review.index',
                'progress.index',
                'kabag.menunggu-kabiro.index',
                'kabag.terdistribusi.index',
                'kabag.rekap.index',
            ],
            'kepala_sub_tim' => [
                'kasubtim.dashboard',
                'kasubtim.penugasan.index',
                'kasubtim.draft.index',
                'progress.index',
                'kasubtim.review.index',
                'kasubtim.riwayat.index',
            ],
            'staf' => [
                'staf.dashboard',
                'staf.tugas.index',
                'staf.buat-surat.index',
                'staf.draf-surat.index',
                'progress.index',
                'staf.selesai.index',
            ],
            'kepala_biro' => [
                'kabiro.dashboard',
                'kabiro.review-final.index',
                'progress.index',
                'kabiro.terdistribusi.index',
                'kabiro.rekap.index',
            ],
        ];

        $hasError = false;

        foreach ($routesByRole as $role => $routes) {
            $this->info("Testing role: $role");
            $user = User::where('role', $role)->first();
            
            if (!$user) {
                $this->error("User not found for role: $role");
                continue;
            }

            foreach ($routes as $routeName) {
                try {
                    $url = route($routeName);
                    $request = \Illuminate\Http\Request::create($url, 'GET');
                    
                    // act as the user
                    auth()->login($user);
                    
                    $response = app()->handle($request);
                    
                    if ($response->status() >= 400) {
                        $this->error("FAILED: $routeName -> HTTP " . $response->status());
                        $hasError = true;
                    } else {
                        $this->line("OK: $routeName -> HTTP " . $response->status());
                    }
                } catch (\Exception $e) {
                    $this->error("ERROR: $routeName -> " . $e->getMessage());
                    $hasError = true;
                }
            }
        }

        if ($hasError) {
            $this->error("Some routes failed verification.");
        } else {
            $this->info("All routes verified successfully.");
        }
    }
}
