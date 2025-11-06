<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BahanPangan;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\BahanPanganExport;
use App\Imports\BahanPanganImport;
use Maatwebsite\Excel\Validators\ValidationException;

class BahanPanganController extends Controller
{
    public function index(Request $request)
    {
        $query = BahanPangan::query();

        if ($request->filled('komoditas')) {
            $query->where('komoditas', $request->komoditas);
        }

        if ($request->filled('pasar')) {
            $query->where('pasar', $request->pasar);
        }

        if ($request->filled('provinsi')) {
            $query->where('provinsi', $request->provinsi);
        }

        $bahanPangans = $query->latest()->get();

        $komoditas = BahanPangan::select('komoditas')->distinct()->pluck('komoditas');
        $pasars = BahanPangan::select('pasar')->distinct()->pluck('pasar');
        $provinsis = BahanPangan::select('provinsi')->distinct()->pluck('provinsi');

        return view('admin.bahan_pangan.index', compact('bahanPangans', 'komoditas', 'pasars', 'provinsis'));
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
            'provinsi' => 'nullable|string|max:255',
            'kabupaten' => 'nullable|string|max:255',
            'kecamatan' => 'nullable|string|max:255',
            'pasar' => 'nullable|string|max:255',
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
            'provinsi' => 'nullable|string|max:255',
            'kabupaten' => 'nullable|string|max:255',
            'kecamatan' => 'nullable|string|max:255',
            'pasar' => 'nullable|string|max:255',
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

    public function exportExcel()
    {
        return Excel::download(new BahanPanganExport, 'bahan_pangan.xlsx');
    }

    public function exportCsv()
    {
        return Excel::download(new BahanPanganExport, 'bahan_pangan.csv', \Maatwebsite\Excel\Excel::CSV);
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls,csv'
        ]);

        try {
            Excel::import(new BahanPanganImport, $request->file('file'));
        } catch (ValidationException $e) {
            $failures = $e->failures();
            $errors = [];
            foreach ($failures as $failure) {
                $errors[] = 'Baris ' . $failure->row() . ': ' . implode(', ', $failure->errors());
            }
            return redirect()->back()->with('error', 'Gagal mengimpor data: ' . implode('; ', $errors));
        }

        return redirect()->route('admin.bahan-pangan.index')
            ->with('success', 'Data bahan pangan berhasil diimpor.');
    }

    public function visualization(Request $request)
    {
        $query = BahanPangan::query();

        if ($request->filled('komoditas')) {
            $query->where('komoditas', $request->komoditas);
        }

        if ($request->filled('provinsi')) {
            $query->where('provinsi', $request->provinsi);
        }

        if ($request->filled('start_date')) {
            $query->whereDate('tanggal', '>=', $request->start_date);
        }

        if ($request->filled('end_date')) {
            $query->whereDate('tanggal', '<=', $request->end_date);
        }

        $bahanPangans = $query->get();

        $komoditas = BahanPangan::select('komoditas')->distinct()->pluck('komoditas');
        $provinsi = BahanPangan::select('provinsi')->distinct()->pluck('provinsi');

        return view('admin.bahan_pangan.visualization', compact('bahanPangans', 'komoditas', 'provinsi'));
    }
}
