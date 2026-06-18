<?php

namespace App\Http\Controllers\Staf;

use App\Http\Controllers\Controller;
use App\Models\Penugasan;
use Illuminate\Http\Request;

class BuatSuratController extends Controller
{
    public function index(Request $request)
    {
        $disposisiQuery = \App\Models\Disposisi::with('suratMasuk', 'pengirim')
            ->where('ke_user_id', auth()->id())
            ->whereIn('status', ['menunggu', 'diproses']);

        if ($request->filled('disposisi_id')) {
            $disposisi = $disposisiQuery->where('id', $request->disposisi_id)->first();
        } else {
            $disposisi = $disposisiQuery->latest()->first();
        }

        $search = $request->query('search');
        $query = \App\Models\TemplateSurat::query();
        if ($search) {
            $query->where('nama_template', 'like', "%{$search}%");
        }
        $templates = $query->latest()->get();

        return view('staf.buat-surat.index', compact('disposisi', 'templates'));
    }
}
