<?php

namespace App\Http\Controllers\Kasubtim;

use App\Http\Controllers\Controller;
use App\Models\DrafSurat;
use Illuminate\Http\Request;

class DraftSuratController extends Controller
{
    public function index(Request $request)
    {
        $tab = $request->query('tab', 'perlu-review');
        
        $query = DrafSurat::with(['pembuat', 'suratMasuk', 'reviuSurat'])
            ->whereHas('suratMasuk.disposisi', function($q) {
                $q->where('dari_user_id', auth()->id());
            });
            
        if ($tab === 'sudah-diproses') {
            $query->whereHas('reviuSurat', function($q) {
                $q->where('tingkat', '1')->where('status', '!=', 'menunggu');
            });
        } else {
            $query->where(function($q) {
                $q->whereHas('reviuSurat', function($q2) {
                    $q2->where('tingkat', '1')->where('status', 'menunggu');
                });
            });
        }
            
        $drafSurats = $query->latest()->paginate(10);
        
        $countPerluReview = DrafSurat::whereHas('suratMasuk.disposisi', function($q) {
                $q->where('dari_user_id', auth()->id());
            })->where(function($q) {
                $q->whereHas('reviuSurat', function($q2) {
                    $q2->where('tingkat', '1')->where('status', 'menunggu');
                })->orWhereDoesntHave('reviuSurat');
            })->count();
            
        return view('kasubtim.draft.index', compact('drafSurats', 'tab', 'countPerluReview'));
    }

    public function show($id)
    {
        $drafSurat = DrafSurat::with(['suratMasuk', 'pembuat.unitKerja', 'reviuSurat' => function($q) {
            $q->orderBy('created_at', 'asc');
        }])->findOrFail($id);
        
        $hasAccess = \App\Models\Disposisi::where('surat_masuk_id', $drafSurat->surat_masuk_id)
            ->where('dari_user_id', auth()->id())
            ->exists();
        abort_unless($hasAccess, 403, 'Anda tidak memiliki akses ke draft ini.');

        return view('kasubtim.draft.show', compact('drafSurat'));
    }
}
