<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\UnitKerja;
use Illuminate\Http\Request;

class UnitController extends Controller
{
    public function index(Request $request)
    {
        $query = UnitKerja::withCount('users')->latest();
        
        if ($search = $request->query('search')) {
            $query->where(function($q) use ($search) {
                $q->where('kode', 'like', "%{$search}%")
                  ->orWhere('nama', 'like', "%{$search}%");
            });
        }
        
        if ($level = $request->query('level')) {
            $query->where('nama', 'like', "%{$level}%");
        }

        $units = $query->paginate(10)->withQueryString();
        return view('admin.units.index', compact('units'));
    }

    public function create()
    {
        return view('admin.units.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'kode' => 'nullable|string|max:50',
            'deskripsi' => 'nullable|string',
        ]);

        UnitKerja::create($request->all());

        return redirect()->route('admin.units.index')->with('success', 'Unit kerja berhasil ditambahkan.');
    }

    public function edit(UnitKerja $unit)
    {
        return view('admin.units.edit', compact('unit'));
    }

    public function update(Request $request, UnitKerja $unit)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'kode' => 'nullable|string|max:50',
            'deskripsi' => 'nullable|string',
        ]);

        $unit->update($request->all());

        return redirect()->route('admin.units.index')->with('success', 'Unit kerja berhasil diperbarui.');
    }

    public function destroy(UnitKerja $unit)
    {
        $unit->delete();
        return redirect()->route('admin.units.index')->with('success', 'Unit kerja berhasil dihapus.');
    }
}
