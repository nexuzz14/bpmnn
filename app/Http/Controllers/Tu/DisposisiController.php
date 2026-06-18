<?php

namespace App\Http\Controllers\Tu;

use App\Http\Controllers\Controller;
use App\Models\SuratMasuk;
use App\Models\Disposisi;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf as DomPDF;

class DisposisiController extends Controller
{
    public function index()
    {
        $suratMasuks = SuratMasuk::with(['disposisi' => function($q) {
            $q->where('dari_user_id', auth()->id());
        }])->where('status', 'diterima')
          ->orWhereHas('disposisi', function ($q) {
              $q->where('dari_user_id', auth()->id());
          })
          ->latest()
          ->paginate(10);
            
        return view('tu.disposisi.index', compact('suratMasuks'));
    }

    public function create(SuratMasuk $suratMasuk)
    {
        // TU usually forwards to Kabag or Kasubtim
        $penerimas = User::whereIn('role', ['kepala_bagian', 'kepala_sub_tim'])->get();
        return view('tu.disposisi.create', compact('suratMasuk', 'penerimas'));
    }

    public function store(Request $request, SuratMasuk $suratMasuk)
    {
        $validated = $request->validate([
            'ke_user_id' => ['required', 'exists:users,id'],
            'instruksi' => ['required', 'string'],
            'tenggat_waktu' => ['required', 'date'],
            'nomor_agenda' => ['nullable', 'string', 'max:255'],
            'catatan' => ['nullable', 'string'],
            'prioritas' => ['nullable', 'string', 'in:biasa,segera,sangat_segera'],
            'file_surat' => ['nullable', 'file', 'mimes:pdf', 'max:2048'],
        ]);

        DB::transaction(function() use ($request, $validated, $suratMasuk) {
            Disposisi::create([
                'surat_masuk_id' => $suratMasuk->id,
                'dari_user_id' => auth()->id(),
                'ke_user_id' => $validated['ke_user_id'],
                'instruksi' => $validated['instruksi'],
                'catatan' => $validated['catatan'] ?? null,
                'tenggat_waktu' => $validated['tenggat_waktu'],
                'prioritas' => $validated['prioritas'] ?? null,
                'status' => 'menunggu'
            ]);

            $fileSuratPath = $suratMasuk->file_surat;
            if ($request->hasFile('file_surat')) {
                // Hapus file lama jika ada
                if ($fileSuratPath && \Illuminate\Support\Facades\Storage::disk('public')->exists($fileSuratPath)) {
                    \Illuminate\Support\Facades\Storage::disk('public')->delete($fileSuratPath);
                }
                $fileSuratPath = $request->file('file_surat')->store('surat-masuk', 'public');
            }

            // Update status surat masuk
            $suratMasuk->update([
                'status' => 'diproses',
                'nomor_agenda' => $validated['nomor_agenda'] ?? $suratMasuk->nomor_agenda,
                'file_surat' => $fileSuratPath,
            ]);
        });

        return redirect()->route('tu.disposisi.index')->with('success', 'Disposisi berhasil dikirim.');
    }

    public function pdf(Disposisi $disposisi)
    {
        $disposisi->load(['suratMasuk', 'pengirim', 'penerima']);
        $pdf = DomPDF::loadView('tu.disposisi.pdf', compact('disposisi'));
        
        return $pdf->stream('Lembar_Disposisi_' . str_replace('/', '_', $disposisi->suratMasuk->nomor_surat) . '.pdf');
    }
}

