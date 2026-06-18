<?php

namespace App\Http\Controllers\Kasubtim;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class BuatSuratController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->query('search');

        $query = \App\Models\TemplateSurat::query();

        if ($search) {
            $query->where('nama_template', 'like', "%{$search}%");
        }

        $templates = $query->latest()->get();

        return view('kasubtim.buat-surat.index', compact('templates'));
    }
}
