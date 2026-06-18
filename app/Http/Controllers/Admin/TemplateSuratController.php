<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TemplateSurat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TemplateSuratController extends Controller
{
    public function index()
    {
        $templates = TemplateSurat::latest()->get();
        return view('admin.template-surat.index', compact('templates'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_template' => 'required|string|max:255',
            'file_template' => 'required|file|mimes:doc,docx,rtf|max:2048',
        ]);

        $path = $request->file('file_template')->store('templates', 'public');

        TemplateSurat::create([
            'nama_template' => $request->nama_template,
            'file_path' => $path,
            'uploaded_by' => auth()->id(),
        ]);

        return redirect()->route('admin.template-surat.index')->with('success', 'Template surat berhasil ditambahkan.');
    }

    public function destroy(TemplateSurat $templateSurat)
    {
        if ($templateSurat->file_path && Storage::disk('public')->exists($templateSurat->file_path)) {
            Storage::disk('public')->delete($templateSurat->file_path);
        }
        $templateSurat->delete();

        return redirect()->route('admin.template-surat.index')->with('success', 'Template surat berhasil dihapus.');
    }
}
