<?php

namespace App\Http\Controllers\Kasubtim;

use App\Http\Controllers\Controller;
use App\Models\Disposisi;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PenugasanController extends Controller
{
    public function index(Request $request)
    {
        $tab = $request->query('tab', 'belum');
        $userId = auth()->id();

        $query = Disposisi::with(['suratMasuk', 'pengirim'])
            ->where('ke_user_id', $userId);

        if ($tab === 'sudah') {
            $query->whereNotIn('status', ['menunggu', 'dibaca']);
        } else {
            $query->whereIn('status', ['menunggu', 'dibaca']);
        }

        $disposisis = $query->latest()->paginate(10);
        $belumCount = Disposisi::where('ke_user_id', $userId)->whereIn('status', ['menunggu', 'dibaca'])->count();

        return view('kasubtim.penugasan.index', compact('disposisis', 'tab', 'belumCount'));
    }

    public function show(Disposisi $penugasan)
    {
        if ($penugasan->status === 'menunggu') {
            $penugasan->update(['status' => 'dibaca']);
        }
        
        $stafs = User::where('role', 'staf')->get();
        return view('kasubtim.penugasan.show', compact('penugasan', 'stafs'));
    }

    public function update(Request $request, Disposisi $penugasan)
    {
        $validated = $request->validate([
            'staf_id' => ['required', 'exists:users,id'],
            'instruksi' => ['required', 'string'],
        ]);

        DB::transaction(function() use ($validated, $penugasan) {
            $penugasan->update(['status' => 'ditindaklanjuti']);

            Disposisi::create([
                'surat_masuk_id' => $penugasan->surat_masuk_id,
                'dari_user_id' => auth()->id(),
                'ke_user_id' => $validated['staf_id'],
                'instruksi' => $validated['instruksi'],
                'tenggat_waktu' => $penugasan->tenggat_waktu,
                'status' => 'menunggu'
            ]);
        });

        return redirect()->route('kasubtim.penugasan.index')->with('success', 'Tugas berhasil diberikan ke Staf.');
    }
}
