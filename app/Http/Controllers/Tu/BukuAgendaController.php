<?php

namespace App\Http\Controllers\Tu;

use App\Http\Controllers\Controller;
use App\Models\SuratMasuk;
use Illuminate\Http\Request;

class BukuAgendaController extends Controller
{
    public function index(Request $request)
    {
        // For TU Biro, Buku Agenda displays all processed incoming mails
        $query = SuratMasuk::with('disposisi.keUser')
                    ->whereNotNull('nomor_agenda');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nomor_agenda', 'like', "%{$search}%")
                  ->orWhere('perihal', 'like', "%{$search}%");
            });
        }

        if ($request->filled('tahun')) {
            $query->whereYear('created_at', $request->tahun);
        }

        if ($request->filled('bulan')) {
            $query->whereMonth('created_at', $request->bulan);
        }

        $agendas = $query->orderBy('created_at', 'desc')->paginate(10)->withQueryString();

        return view('tu.buku-agenda.index', compact('agendas'));
    }

    public function export(Request $request)
    {
        $bulan = $request->query('bulan', now()->month);
        $tahun = $request->query('tahun', now()->year);

        return \Maatwebsite\Excel\Facades\Excel::download(new \App\Exports\BukuAgendaExport($bulan, $tahun), "Buku_Agenda_{$bulan}_{$tahun}.xlsx");
    }
}
