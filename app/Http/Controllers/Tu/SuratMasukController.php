<?php

namespace App\Http\Controllers\Tu;

use App\Http\Controllers\Controller;
use App\Models\SuratMasuk;
use Illuminate\Http\Request;

class SuratMasukController extends Controller
{
    public function index(Request $request)
    {
        $query = SuratMasuk::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nomor_surat', 'like', "%{$search}%")
                  ->orWhere('perihal', 'like', "%{$search}%")
                  ->orWhere('asal_surat', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status') && $request->status !== '') {
            if ($request->status === 'belum_diproses') {
                $query->doesntHave('disposisi');
            } elseif ($request->status === 'sudah_didisposisi') {
                $query->has('disposisi');
            }
        }

        $suratMasuks = $query->latest()->paginate(10)->withQueryString();
        return view('tu.surat-masuk.index', compact('suratMasuks'));
    }

    public function show(SuratMasuk $suratMasuk)
    {
        $activityLogs = \App\Models\ActivityLog::where(function($query) use ($suratMasuk) {
            $query->where('model_type', SuratMasuk::class)
                  ->where('model_id', $suratMasuk->id);
        })->latest()->get();

        return view('tu.surat-masuk.show', compact('suratMasuk', 'activityLogs'));
    }

    public function destroy(SuratMasuk $suratMasuk)
    {
        // Prevent deletion if the letter is already processed/distributed
        if ($suratMasuk->status === 'selesai' || $suratMasuk->status === 'terdistribusi') {
            return redirect()->back()->with('error', 'Surat yang sudah selesai atau terdistribusi tidak dapat dihapus.');
        }

        if ($suratMasuk->file_surat && \Illuminate\Support\Facades\Storage::disk('public')->exists($suratMasuk->file_surat)) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($suratMasuk->file_surat);
        }
        $suratMasuk->delete();

        return redirect()->route('tu.surat-masuk.index')->with('success', 'Surat masuk berhasil dihapus.');
    }
}
