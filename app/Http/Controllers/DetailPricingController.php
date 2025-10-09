<?php

namespace App\Http\Controllers;

use App\Models\DetailPricing;
use App\Models\Pricing;
use Illuminate\Http\Request;

class DetailPricingController extends Controller
{
    /**
     * Tampilkan semua detail pricing
     */
    public function index()
    {
        $details = DetailPricing::with('pricing')->latest()->get();
        $pricings = Pricing::all();
        $types = DetailPricing::select('type')->distinct()->pluck('type');

        return view('detail-pricings.index', compact('details', 'pricings', 'types'));
    }

    /**
     * Filter detail pricing berdasarkan type
     */
    public function getByType($type)
    {
        $details = DetailPricing::with('pricing')
            ->where('type', $type)
            ->latest()
            ->get();

        $pricings = Pricing::all();
        $types = DetailPricing::select('type')->distinct()->pluck('type');
        $selectedType = $type;

        return view('detail-pricings.index', compact('details', 'pricings', 'types', 'selectedType'));
    }

    /**
     * Simpan data baru
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_pricings' => 'nullable|exists:pricings,id',
            'name'        => 'required|array',
            'name.*'      => 'string|max:255',
            'deskripsi'   => 'nullable|string',
            'deskripsi2'  => 'nullable|string',
            'status'      => 'required|array',
            'status.*'    => 'string|max:50',
            'keuntungan'  => 'nullable|string',
            'type'        => 'required|string|max:255',
        ]);

        // Filter array kosong
        $validated['name'] = array_filter($validated['name'], fn($v) => !empty(trim($v)));
        $validated['status'] = array_filter($validated['status'], fn($v) => !empty(trim($v)));

        DetailPricing::create([
            'id_pricings' => $validated['id_pricings'] ?? null,
            'name'        => array_values($validated['name']),
            'deskripsi'   => $validated['deskripsi'] ?? '',
            'deskripsi2'  => $validated['deskripsi2'] ?? '',
            'status'      => array_values($validated['status']),
            'keuntungan'  => $validated['keuntungan'] ?? '',
            'type'        => $validated['type'],
        ]);

        return redirect()->back()->with('success', 'Detail Pricing berhasil ditambahkan!');
    }

    /**
     * Ambil data untuk edit (JSON)
     */
    public function edit($id)
    {
        $detail = DetailPricing::with('pricing')->findOrFail($id);
        return response()->json($detail);
    }

    /**
     * Ambil data untuk detail view (JSON)
     */
    public function show($id)
    {
        $detail = DetailPricing::with('pricing')->findOrFail($id);
        return response()->json($detail);
    }

    /**
     * Update data
     */
    public function update(Request $request, $id)
    {
        $detail = DetailPricing::findOrFail($id);

        $validated = $request->validate([
            'id_pricings' => 'nullable|exists:pricings,id',
            'name'        => 'required|array',
            'name.*'      => 'string|max:255',
            'deskripsi'   => 'nullable|string',
            'deskripsi2'  => 'nullable|string',
            'status'      => 'required|array',
            'status.*'    => 'string|max:50',
            'keuntungan'  => 'nullable|string',
            'type'        => 'required|string|max:255',
        ]);

        // Filter array kosong
        $validated['name'] = array_filter($validated['name'], fn($v) => !empty(trim($v)));
        $validated['status'] = array_filter($validated['status'], fn($v) => !empty(trim($v)));

        $detail->update([
            'id_pricings' => $validated['id_pricings'] ?? null,
            'name'        => array_values($validated['name']),
            'deskripsi'   => $validated['deskripsi'] ?? '',
            'deskripsi2'  => $validated['deskripsi2'] ?? '',
            'status'      => array_values($validated['status']),
            'keuntungan'  => $validated['keuntungan'] ?? '',
            'type'        => $validated['type'],
        ]);

        return redirect()->back()->with('success', 'Detail Pricing berhasil diperbarui!');
    }

    /**
     * Hapus data
     */
    public function destroy($id)
    {
        $detail = DetailPricing::findOrFail($id);
        $detail->delete();

        return redirect()->back()->with('success', 'Detail Pricing berhasil dihapus!');
    }
}
