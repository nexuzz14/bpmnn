<?php

namespace App\Http\Controllers\Kabag;

use App\Http\Controllers\Controller;
use App\Models\Disposisi;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DisposisiController extends Controller
{
    public function index(Request $request)
    {
        $query = Disposisi::with('suratMasuk')
            ->where('ke_user_id', auth()->id());

        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('suratMasuk', function($q) use ($search) {
                $q->where('nomor_surat', 'like', "%{$search}%")
                  ->orWhere('perihal', 'like', "%{$search}%")
                  ->orWhere('asal_surat', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status') && $request->status !== 'Semua Status' && $request->status !== '') {
            $statusMap = [
                'Belum Dibaca' => 'menunggu',
                'Sedang Diproses' => ['dibaca', 'diproses', 'ditindaklanjuti'],
                'Selesai' => 'selesai'
            ];
            
            $mappedStatus = $statusMap[$request->status] ?? null;
            if ($mappedStatus) {
                if (is_array($mappedStatus)) {
                    $query->whereIn('status', $mappedStatus);
                } else {
                    $query->where('status', $mappedStatus);
                }
            }
        }
        
        if ($request->filled('prioritas') && $request->prioritas !== '') {
            $query->whereHas('suratMasuk', function($q) use ($request) {
                $q->where('prioritas', strtolower($request->prioritas));
            });
        }

        $disposisis = $query->latest()->paginate(10)->withQueryString();
            
        return view('kabag.disposisi.index', compact('disposisis'));
    }

    public function detail(Disposisi $disposisi)
    {
        if ($disposisi->status === 'menunggu') {
            $disposisi->update(['status' => 'dibaca']);
        }
        
        return view('kabag.disposisi.detail', compact('disposisi'));
    }

    public function show(Disposisi $disposisi)
    {
        if ($disposisi->status === 'menunggu') {
            $disposisi->update(['status' => 'dibaca']);
        }
        
        $penerimas = User::where('role', 'kepala_sub_tim')->get();
        return view('kabag.disposisi.show', compact('disposisi', 'penerimas'));
    }

    public function update(Request $request, Disposisi $disposisi)
    {
        // Forward ke Kasubtim
        $validated = $request->validate([
            'kasubtim_id' => ['required', 'exists:users,id'],
            'instruksi' => ['required', 'string'],
        ]);

        DB::transaction(function() use ($validated, $disposisi) {
            // Ubah status disposisi sekarang jadi ditindaklanjuti
            $disposisi->update(['status' => 'ditindaklanjuti']);

            // Buat disposisi baru ke Kasubtim
            Disposisi::create([
                'surat_masuk_id' => $disposisi->surat_masuk_id,
                'dari_user_id' => auth()->id(),
                'ke_user_id' => $validated['kasubtim_id'],
                'instruksi' => $validated['instruksi'],
                'tenggat_waktu' => $disposisi->tenggat_waktu, // Ikut tenggat waktu awal
                'status' => 'menunggu'
            ]);
        });

        return redirect()->route('kabag.disposisi.index')->with('success', 'Disposisi berhasil diteruskan ke Kepala Sub Tim.');
    }
}
