<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SuratMasuk;
use App\Models\ActivityLog;
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

        if ($request->filled('status') && $request->status !== 'Semua Status' && $request->status !== '') {
            $query->where('status', strtolower(str_replace(' ', '_', $request->status)));
        }

        $suratMasuks = $query->latest()->paginate(10)->withQueryString();
        return view('admin.surat-masuk.index', compact('suratMasuks'));
    }

    public function create()
    {
        return view('admin.surat-masuk.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nomor_surat' => ['required', 'string', 'max:255', 'unique:surat_masuks'],
            'tanggal_surat' => ['required', 'date'],
            'tanggal_terima' => ['required', 'date'],
            'asal_surat' => ['required', 'string', 'max:255'],
            'perihal' => ['required', 'string', 'max:255'],
            'sifat' => ['required', 'in:segera,biasa'],
            'jenis_surat' => ['required', 'in:fisik,digital'],
            'file_surat' => ['nullable', 'file', 'mimes:pdf,doc,docx', 'max:10240'], // Max 10MB PDF/Word
            'keterangan' => ['nullable', 'string'],
        ]);

        if ($request->hasFile('file_surat')) {
            $path = $request->file('file_surat')->store('surat-masuk', 'public');
            $validated['file_surat'] = $path;
        }

        $validated['created_by'] = auth()->id();
        $validated['status'] = 'diterima';

        SuratMasuk::create($validated);

        return redirect()->route('admin.surat-masuk.index')->with('success', 'Surat Masuk berhasil ditambahkan.');
    }

    public function show(SuratMasuk $suratMasuk)
    {
        $activityLogs = ActivityLog::where(function($query) use ($suratMasuk) {
            $query->where('model_type', SuratMasuk::class)
                  ->where('model_id', $suratMasuk->id);
        })->latest()->get();

        return view('admin.surat-masuk.show', compact('suratMasuk', 'activityLogs'));
    }

    public function edit(SuratMasuk $suratMasuk)
    {
        return view('admin.surat-masuk.edit', compact('suratMasuk'));
    }

    public function update(Request $request, SuratMasuk $suratMasuk)
    {
        $validated = $request->validate([
            'nomor_surat' => ['required', 'string', 'max:255', 'unique:surat_masuks,nomor_surat,' . $suratMasuk->id],
            'tanggal_surat' => ['required', 'date'],
            'tanggal_terima' => ['required', 'date'],
            'asal_surat' => ['required', 'string', 'max:255'],
            'perihal' => ['required', 'string', 'max:255'],
            'sifat' => ['required', 'in:segera,biasa'],
            'jenis_surat' => ['required', 'in:fisik,digital'],
            'file_surat' => ['nullable', 'file', 'mimes:pdf,doc,docx', 'max:10240'],
            'keterangan' => ['nullable', 'string'],
        ]);

        if ($request->hasFile('file_surat')) {
            $path = $request->file('file_surat')->store('surat-masuk', 'public');
            $validated['file_surat'] = $path;
        }

        $suratMasuk->update($validated);

        return redirect()->route('admin.surat-masuk.index')->with('success', 'Surat Masuk berhasil diperbarui.');
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
        return redirect()->route('admin.surat-masuk.index')->with('success', 'Surat Masuk berhasil dihapus.');
    }
}
