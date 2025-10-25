<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BahanPangan;
use Illuminate\Http\Request;

class BahanPanganController extends Controller
{
    public function index()
    {
        $bahanPangans = BahanPangan::latest()->get();
        return view('admin.bahan_pangan.index', compact('bahanPangans'));
    }

    public function create()
    {
        return view('admin.bahan_pangan.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'komoditas' => 'required|string|max:255',
            'tanggal' => 'required|date',
            'harga' => 'required|integer|min:0',
            'kategori' => 'required|string|max:255',
        ]);

        BahanPangan::create($validated);

        return redirect()->route('admin.bahan-pangan.index')
            ->with('success', 'Data bahan pangan berhasil ditambahkan.');
    }

    public function show(string $id)
    {
        $bahanPangan = BahanPangan::findOrFail($id);
        return view('admin.bahan_pangan.show', compact('bahanPangan'));
    }

    public function edit(string $id)
    {
        $bahanPangan = BahanPangan::findOrFail($id);
        return view('admin.bahan_pangan.edit', compact('bahanPangan'));
    }

    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
            'komoditas' => 'required|string|max:255',
            'tanggal' => 'required|date',
            'harga' => 'required|integer|min:0',
            'kategori' => 'required|string|max:255',
        ]);

        $bahanPangan = BahanPangan::findOrFail($id);
        $bahanPangan->update($validated);

        return redirect()->route('admin.bahan-pangan.index')
            ->with('success', 'Data bahan pangan berhasil diperbarui.');
    }

    public function destroy(string $id)
    {
        $bahanPangan = BahanPangan::findOrFail($id);
        $bahanPangan->delete();

        return redirect()->route('admin.bahan-pangan.index')
            ->with('success', 'Data bahan pangan berhasil dihapus.');
    }
}
