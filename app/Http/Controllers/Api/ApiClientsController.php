<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Clients;
use Illuminate\Http\Request;

class ApiClientsController extends Controller
{
public function index(Request $request)
{
    $query = Clients::query();

    if ($request->filled('status')) {
        $query->where('status', $request->status);
    }

    $data = $query->get()->map(function($client) {

        return [
            'id'    => $client->id,
            'name'  => $client->name,
            'status'=> $client->status,

            // ✔ FIX: benar, tidak double folder
            'foto_client' => $client->foto_client 
                ? asset('storage/' . $client->foto_client)
                : null,
        ];
    });

    return response()->json([
        'data' => $data,
    ]);
}


}