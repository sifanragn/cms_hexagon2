<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\DetailPricing;
use Illuminate\Http\Request;

class ApiDetailPricingController extends Controller
{
    /**
     * 🔹 GET: /api/detail-pricings
     * Ambil semua detail pricing (beserta relasi pricing)
     */
    public function index()
    {
        $details = DetailPricing::with('pricing')->latest()->get();

        return response()->json([
            'success' => true,
            'message' => 'List semua Detail Pricing',
            'data' => $details
        ]);
    }

    /**
     * 🔹 GET: /api/detail-pricings/type/{type}
     * Ambil detail pricing berdasarkan type tertentu
     */
    public function getByType($type)
    {
        $details = DetailPricing::with('pricing')
            ->where('type', $type)
            ->latest()
            ->get();

        return response()->json([
            'success' => true,
            'message' => "List Detail Pricing dengan type: {$type}",
            'data' => $details
        ]);
    }

    /**
     * 🔹 GET: /api/detail-pricings/{id}
     * Ambil satu detail pricing berdasarkan ID
     */
    public function show($id)
    {
        $detail = DetailPricing::with('pricing')->find($id);

        if (!$detail) {
            return response()->json([
                'success' => false,
                'message' => 'Detail Pricing tidak ditemukan'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Detail Pricing ditemukan',
            'data' => $detail
        ]);
    }
}
