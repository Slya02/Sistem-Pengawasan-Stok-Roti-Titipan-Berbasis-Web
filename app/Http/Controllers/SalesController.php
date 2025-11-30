<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use App\Models\StokRoti;
use App\Models\Store;
use Carbon\Carbon;

class SalesController extends Controller
{
    /** 🏠 Halaman Home */
    public function home(Request $request)
    {
        $salesId = Auth::id();

        // Filter tampilan (untuk tabel & total pendapatan)
        $filter = $request->filter ?? 'semua';

        $query = \App\Models\SisaRoti::where('user_id', $salesId);

        if ($filter === 'hari') {
            $query->whereDate('tanggal_pengambilan', today());
        }
        elseif ($filter === 'minggu') {
            $query->where('tanggal_pengambilan', '>=', now()->subDays(7));
        }
        elseif ($filter === 'bulan') {
            $query->where('tanggal_pengambilan', '>=', now()->subDays(30));
        }

        $historiPenjualan = $query->orderBy('tanggal_pengambilan', 'desc')->get();
        $totalPendapatan = $historiPenjualan->sum('total_bill');

        // =======================================================
        //  TARGET SALES BULAN INI (tidak terpengaruh filter)
        // =======================================================
        $bulanSekarang = now()->format('Y-m');

        $target = \App\Models\SalesTarget::firstOrCreate(
            [
                'user_id' => $salesId,
                'bulan'   => $bulanSekarang
            ],
            [
                'target_bulanan' => 0,
                'tercapai'       => 0
            ]
        );

        // hitung total pencapaian bulan ini
        $totalPendapatanBulanIni = \App\Models\SisaRoti::where('user_id', $salesId)
            ->whereYear('tanggal_pengambilan', now()->year)
            ->whereMonth('tanggal_pengambilan', now()->month)
            ->sum('total_bill');

        // update value agar bisa dipakai di blade
        $target->tercapai = $totalPendapatanBulanIni;
        $target->sisa_target = max($target->target_bulanan - $totalPendapatanBulanIni, 0);

        return view('sales.home', compact(
            'totalPendapatan',
            'historiPenjualan',
            'filter',
            'target'
        ));
    }



    /** 📜 Histori Stok Masuk + Penjualan */
    public function histori(Request $request)
    {
        $salesId = Auth::id();

        // Ambil filter waktu dari request
        // nilai default "minggu" (7 hari terakhir)
        $filter = $request->filter ?? 'minggu';

        // Query stok masuk
        $queryMasuk = StokRoti::where('user_id', $salesId);

        // Query histori penjualan
        $queryPenjualan = \App\Models\SisaRoti::where('user_id', $salesId);


        /* ===============================================================
        FILTER TANGGAL
        =============================================================== */
        if ($filter === 'hari') {
            // hanya hari ini
            $queryMasuk->whereDate('tanggal_pengantaran', today());
            $queryPenjualan->whereDate('tanggal_pengambilan', today());
        }
        elseif ($filter === 'minggu') {
            // 7 hari terakhir
            $queryMasuk->where('tanggal_pengantaran', '>=', now()->subDays(7));
            $queryPenjualan->where('tanggal_pengambilan', '>=', now()->subDays(7));
        }
        elseif ($filter === 'bulan') {
            // 30 hari terakhir
            $queryMasuk->where('tanggal_pengantaran', '>=', now()->subDays(30));
            $queryPenjualan->where('tanggal_pengambilan', '>=', now()->subDays(30));
        }
        // jika "semua", tidak ada filter tanggal


        /* ===============================================================
        FILTER NAMA TOKO
        =============================================================== */
        if ($request->toko) {
            $search = $request->toko;

            $queryMasuk->where('nama_toko', 'like', "%$search%");
            $queryPenjualan->where('nama_toko', 'like', "%$search%");
        }


        /* ===============================================================
        AMBIL DATA AKHIR
        =============================================================== */
        $historiMasuk = $queryMasuk
            ->orderBy('tanggal_pengantaran', 'desc')
            ->get();

        $historiPenjualan = $queryPenjualan
            ->orderBy('tanggal_pengambilan', 'desc')
            ->get();


        /* ===============================================================
        KIRIM KE VIEW
        =============================================================== */
        return view('sales.histori', compact('historiMasuk', 'historiPenjualan'));
    }


    /** 📍 Lokasi Toko */
    public function lokasi()
    {
        $stores = Store::where('sales_id', Auth::id())->get();
        return view('sales.lokasi_toko', compact('stores'));
    }

    /** 📦 Form Input Stok */
    public function create()
    {
        $stores = Store::where('sales_id', Auth::id())->get();

        // Ambil varian dari kolom stok_rotis
        $columns = Schema::getColumnListing('stok_rotis');
        $variants = [];
        foreach ($columns as $col) {
            if (str_contains($col, '_masuk')) {
                $variants[] = str_replace('_masuk', '', $col);
            }
        }

        return view('sales.input_stok', compact('stores', 'variants'));
    }

    public function storeStok(Request $request)
    {
        // Ambil varian otomatis dari database
        $columns = Schema::getColumnListing('stok_rotis');
        $variants = [];
        foreach ($columns as $col) {
            if (str_contains($col, '_masuk')) {
                $variants[] = str_replace('_masuk', '', $col);
            }
        }

        // VALIDASI TANPA FOTO
        $rules = [
            'nama_toko' => 'required|string|max:255',
            'tanggal_pengantaran' => 'required|date',
        ];

        foreach ($variants as $v) {
            $rules[$v . '_masuk'] = 'required|integer|min:0';
        }

        $request->validate($rules);

        // DATA SIMPAN
        $data = [
            'user_id' => Auth::id(),
            'nama_toko' => $request->nama_toko,
            'tanggal_pengantaran' => $request->tanggal_pengantaran,
        ];

        foreach ($variants as $v) {
            $data[$v . '_masuk'] = $request->input($v . '_masuk', 0);
        }

        StokRoti::create($data);

        return redirect()->route('sales.histori')->with('success', 'Data stok berhasil disimpan!');
    }

    /** 🌍 Update Lokasi Toko */
    public function updateLokasi(Request $request, $id)
    {
        $request->validate([
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
        ]);

        $store = Store::findOrFail($id);
        $store->update([
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
        ]);

        return redirect()->back()->with('success', 'Lokasi toko berhasil diperbarui!');
    }
}
