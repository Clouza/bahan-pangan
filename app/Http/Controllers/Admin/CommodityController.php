<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Commodity;

class CommodityController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $commodities = Commodity::latest()->get();
        return view('admin.commodities.index', compact('commodities'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.commodities.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'date' => 'required|date',
            'price' => 'required|numeric|min:0',
            'type' => 'required|string|max:255',
        ]);

        Commodity::create($validated);

        return redirect()->route('admin.commodities.index')
            ->with('success', 'Data komoditas berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $commodity = Commodity::findOrFail($id);
        return view('admin.commodities.edit', compact('commodity'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'date' => 'required|date',
            'price' => 'required|numeric|min:0',
            'type' => 'required|string|max:255',
        ]);

        $commodity = Commodity::findOrFail($id);
        $commodity->update($validated);

        return redirect()->route('admin.commodities.index')
            ->with('success', 'Data komoditas berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $commodity = Commodity::findOrFail($id);
        $commodity->delete();

        return redirect()->route('admin.commodities.index')
            ->with('success', 'Data komoditas berhasil dihapus.');
    }
}
