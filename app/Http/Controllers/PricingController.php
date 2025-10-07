<?php

namespace App\Http\Controllers;

use App\Models\Pricing;
use Illuminate\Http\Request;

class PricingController extends Controller
{
    /**
     * Tampilkan semua data pricing.
     */
    public function index()
    {
        $pricings = Pricing::latest()->get();
        return view('pricing.index', compact('pricings'));
    }

    /**
     * Simpan data baru.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama'      => 'required|string|max:255',
            'title'     => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'deskripsi2' => 'nullable|string',
            'price'     => 'required|numeric|min:0',
            'diskon'    => 'nullable|numeric|min:0|max:100',
            'status'    => 'required|string|max:50',
        ]);

        Pricing::create($request->all());

        return redirect()->back()->with('success', 'Pricing berhasil ditambahkan!');
    }

    /**
     * Show detail data pricing.
     */
   public function show($id)
{
    $pricing = Pricing::findOrFail($id);

    // Kalau request AJAX atau minta JSON → balikin JSON
    if (request()->ajax() || request()->wantsJson()) {
        return response()->json($pricing);
    }

    // Kalau buka langsung lewat URL → tampilkan view detail
    return view('pricing.show', compact('pricing'));
}


    /**
     * Update data.
     */
    public function update(Request $request, $id)
    {
        $pricing = Pricing::findOrFail($id);

        $request->validate([
            'nama'      => 'required|string|max:255',
            'title'     => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'deskripsi2' => 'nullable|string',
            'price'     => 'required|numeric|min:0',
            'diskon'    => 'nullable|numeric|min:0|max:100',
            'status'    => 'required|string|max:50',
        ]);

        $pricing->update($request->all());

        return redirect()->back()->with('success', 'Pricing berhasil diperbarui!');
    }

    /**
     * Hapus data.
     */
    public function destroy($id)
    {
        $pricing = Pricing::findOrFail($id);
        $pricing->delete();

        return redirect()->back()->with('success', 'Pricing berhasil dihapus!');
    }
}
