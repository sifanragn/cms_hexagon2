<?php

namespace App\Http\Controllers;

use App\Models\DetailPricing;
use App\Models\Pricing;
use Illuminate\Http\Request;

class DetailPricingController extends Controller
{
    public function index()
    {
        $details = DetailPricing::with('pricing')->latest()->get();
        $pricings = Pricing::all();
        $types = DetailPricing::select('type')->distinct()->pluck('type'); // 🔹 ambil semua type unik

        return view('detail-pricings.index', compact('details', 'pricings', 'types'));
    }

    public function getByType($type)
    {
        $details = DetailPricing::with('pricing')
            ->where('type', $type)
            ->latest()
            ->get();

        $pricings = Pricing::all();
        $types = DetailPricing::select('type')->distinct()->pluck('type'); // tetap kirim semua type
        $selectedType = $type; // 🔹 kirim type yang sedang aktif

        return view('detail-pricings.index', compact('details', 'pricings', 'types', 'selectedType'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_pricings' => 'nullable|exists:pricings,id',
            'name'        => 'required|string|max:255',
            'deskripsi'   => 'nullable|string',
            'keuntungan'  => 'nullable|string',
            'status'      => 'required|string|max:50',
            'type'        => 'required|string|max:255',
        ]);

        DetailPricing::create($request->all());
        return redirect()->back()->with('success', 'Detail Pricing berhasil ditambahkan!');
    }

    public function update(Request $request, $id)
    {
        $detail = DetailPricing::findOrFail($id);
        $request->validate([
            'id_pricings' => 'nullable|exists:pricings,id',
            'name'        => 'required|string|max:255',
            'deskripsi'   => 'nullable|string',
            'keuntungan'  => 'nullable|string',
            'status'      => 'required|string|max:50',
            'type'        => 'required|string|max:255',
        ]);

        $detail->update($request->all());
        return redirect()->back()->with('success', 'Detail Pricing berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $detail = DetailPricing::findOrFail($id);
        $detail->delete();

        return redirect()->back()->with('success', 'Detail Pricing berhasil dihapus!');
    }
}
