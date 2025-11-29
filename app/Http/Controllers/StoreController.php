<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\Store;

class StoreController extends Controller
{
    /** 🧭 Tampilkan daftar toko */
    public function index()
    {
        $stores = Store::where('sales_id', Auth::id())->latest()->get();
        return view('sales.daftar_toko', compact('stores'));
    }

    /** ➕ Form tambah toko */
    public function create()
    {
        return view('sales.tambah_toko');
    }

    /** 🗑️ Hapus toko */
    public function destroy($id)
    {
        try {
            $store = Store::findOrFail($id);

            // Hapus file foto dari storage
            if (is_array($store->photo)) {
                foreach ($store->photo as $file) {
                    Storage::disk('public')->delete($file);
                }
            }

            $store->delete();

            return redirect()->route('sales.daftartoko')
                ->with('success', 'Toko berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menghapus toko: ' . $e->getMessage());
        }
    }

    /** 💾 Simpan toko baru (AJAX) */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'name'       => 'required|string|max:255',
                'phone'      => 'required|numeric',
                'owner_name' => 'required|string|max:255',
                'address'    => 'required|string|max:255',
                'join_date'  => 'required|date',
                'latitude'   => 'nullable|numeric|between:-90,90',
                'longitude'  => 'nullable|numeric|between:-180,180',
                'photo.*'    => 'nullable|image|mimes:jpg,jpeg,png|max:5120',
            ]);

            /** Foto Upload */
            $paths = [];
            if ($request->hasFile('photo')) {
                foreach ($request->file('photo') as $file) {
                    $paths[] = $file->store('foto_toko', 'public');
                }
            }

            /** SIMPAN KE DATABASE DENGAN JSON STRING */
            Store::create([
                'name'       => $validated['name'],
                'phone'      => $validated['phone'],
                'owner_name' => $validated['owner_name'],
                'address'    => $validated['address'],
                'join_date'  => $validated['join_date'],
                'latitude'   => $validated['latitude'] ?? null,
                'longitude'  => $validated['longitude'] ?? null,
                'photo'      => json_encode($paths), // <<<<< FIX UTAMA
                'sales_id'   => Auth::id(),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Data toko dan lokasi berhasil disimpan!',
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menyimpan data toko: ' . $e->getMessage(),
            ], 500);
        }
    }
}
