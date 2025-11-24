<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Clients;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class ClientsController extends Controller
{
    /** Tampilkan daftar client. */
    public function index()
    {
        $clients = Clients::latest()->get();
        return view('about.clients', compact('clients'));
    }

    /** Simpan client baru. */
    public function store(Request $request)
    {
        $request->validate([
        'name' => 'required|string|max:255',
        'status' => 'required|in:0,1,2,3',
        'foto_client' => 'required|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $file = $request->file('foto_client');
        $fileName = Str::random(20) . '.' . $file->extension();

        // Simpan ke storage/app/public/foto_client
        $path = $file->storeAs('foto_client', $fileName, 'public');

        Clients::create([
            'name' => $request->name,
            'status' => $request->status,
            'foto_client' => $path, // simpan path relative ke storage/public
        ]);

        return redirect()->back()->with('success', 'Client berhasil ditambahkan');
    }

    /** Update data client. */
    public function update(Request $request, Clients $client)
    {
        $validated = $request->validate([
        'name'        => 'required|string|max:255',
        'status'      => 'required|in:0,1,2,3',
        'foto_client' => 'sometimes|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        if ($request->hasFile('foto_client')) {
            // Hapus file lama kalau ada
            if ($client->foto_client && Storage::disk('public')->exists($client->foto_client)) {
                Storage::disk('public')->delete($client->foto_client);
            }

            // Simpan file baru
            $file = $request->file('foto_client');
            $fileName = Str::random(20) . '.' . $file->extension();
            $path = $file->storeAs('foto_client', $fileName, 'public');

            $validated['foto_client'] = $path;
        }

        $client->update($validated);

        return redirect()->back()->with('success', 'Client berhasil diperbarui');
    }

    /** Hapus client. */
    public function destroy(Clients $client)
    {
        if ($client->foto_client && Storage::disk('public')->exists($client->foto_client)) {
            Storage::disk('public')->delete($client->foto_client);
        }

        $client->delete();

        return redirect()->back()->with('success', 'Client berhasil dihapus');
    }
}
