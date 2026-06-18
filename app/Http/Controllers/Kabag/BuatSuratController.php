<?php

namespace App\Http\Controllers\Kabag;

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

        return view('kabag.buat-surat.index', compact('templates'));
    }
}
