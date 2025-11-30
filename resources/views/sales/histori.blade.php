@extends('layouts.app')

@section('title', 'Histori Penjualan')

@section('content')

@php
    /*
    |--------------------------------------------------------------------------
    | Format Varian Dalam Bentuk Bullet List + Total
    |--------------------------------------------------------------------------
    */
    function formatVarianListWithTotal($item, $suffix) {
        $data = collect($item->toArray())
            ->filter(fn($v, $k) => str_ends_with($k, $suffix));

        $list = $data->map(function($v, $k) use ($suffix) {
                $nama = str_replace($suffix, '', $k);
                $nama = str_replace('_', ' ', $nama);
                return '<li class="ml-4">• <span class="capitalize">'. $nama .'</span>: <b>'. intval($v) .'</b></li>';
            })
            ->implode('');

        $total = $data->map(fn($v) => intval($v))->sum();

        return $list . '<hr class="my-1"><b>Total: '. $total .' roti</b>';
    }
@endphp

<div class="p-4 sm:p-8">

    {{-- ========================= --}}
    {{-- FILTER & SEARCH --}}
    {{-- ========================= --}}
    <form method="GET" class="mb-6 flex flex-col sm:flex-row gap-4">

        <input type="text"
               name="toko"
               value="{{ request('toko') }}"
               placeholder="Cari nama toko..."
               class="px-4 py-2 border border-gray-300 rounded-lg w-full sm:w-1/3">

        <select name="filter"
                class="px-4 py-2 border border-gray-300 rounded-lg w-full sm:w-1/4">
            <option value="hari"   {{ request('filter') === 'hari' ? 'selected' : '' }}>Hari ini</option>
            <option value="minggu" {{ request('filter') === 'minggu' ? 'selected' : '' }}>7 hari terakhir</option>
            <option value="bulan"  {{ request('filter') === 'bulan' ? 'selected' : '' }}>30 hari terakhir</option>
            <option value="semua"  {{ request('filter') === 'semua' ? 'selected' : '' }}>Semua data</option>
        </select>

        <button class="px-6 py-2 bg-yellow-400 hover:bg-yellow-500 rounded-lg font-semibold">
            Terapkan
        </button>
    </form>



    {{-- ========================= --}}
    {{-- HISTORI ROTI MASUK --}}
    {{-- ========================= --}}
    <h1 class="text-2xl font-bold text-gray-800 mb-4">Histori Roti Masuk</h1>

    @if ($historiMasuk->isEmpty())

        <div class="bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-4 rounded mb-6">
            Belum ada data roti masuk pada periode ini.
        </div>

    @else

        {{-- DESKTOP TABLE --}}
        <div class="hidden sm:block bg-white shadow rounded-lg overflow-x-auto mb-8">
            <table class="min-w-full border-collapse">
                <thead class="bg-yellow-300 text-black">
                    <tr>
                        <th class="px-4 py-3 text-left">Tanggal</th>
                        <th class="px-4 py-3 text-left">Nama Sales</th>
                        <th class="px-4 py-3 text-left">Nama Toko</th>
                        <th class="px-4 py-3 text-left">Roti Masuk</th>
                    </tr>
                </thead>

                <tbody class="divide-y">
                    @foreach ($historiMasuk as $item)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3">
                                {{ \Carbon\Carbon::parse($item->tanggal_pengantaran)->format('d M Y') }}
                            </td>
                            <td class="px-4 py-3">{{ $item->user->name ?? '-' }}</td>
                            <td class="px-4 py-3">{{ $item->nama_toko }}</td>

                            <td class="px-4 py-3">
                                <ul class="list-none">{!! formatVarianListWithTotal($item, '_masuk') !!}</ul>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- MOBILE CARD --}}
        <div class="sm:hidden space-y-4 mb-8">

            @foreach ($historiMasuk as $item)

                <div class="bg-white border border-yellow-300 rounded-lg shadow p-4">

                    <p class="font-bold text-lg">
                        {{ \Carbon\Carbon::parse($item->tanggal_pengantaran)->format('d M Y') }}
                    </p>

                    <p><b>Sales:</b> {{ $item->user->name ?? '-' }}</p>
                    <p><b>Toko:</b> {{ $item->nama_toko }}</p>

                    <p class="mt-2 font-semibold">Roti Masuk:</p>
                    <ul>{!! formatVarianListWithTotal($item, '_masuk') !!}</ul>

                </div>

            @endforeach

        </div>

    @endif



    {{-- ========================= --}}
    {{-- HISTORI PENJUALAN --}}
    {{-- ========================= --}}
    <h1 class="text-2xl font-bold text-gray-800 mb-4">Histori Penjualan</h1>

    @if ($historiPenjualan->isEmpty())

        <div class="bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-4 rounded">
            Belum ada data penjualan pada periode ini.
        </div>

    @else

        {{-- DESKTOP TABLE --}}
        <div class="hidden sm:block bg-white shadow rounded-lg overflow-x-auto">
            <table class="min-w-full border-collapse">
                <thead class="bg-yellow-300">
                    <tr>
                        <th class="px-4 py-3">Tanggal</th>
                        <th class="px-4 py-3">Sales</th>
                        <th class="px-4 py-3">Toko</th>
                        <th class="px-4 py-3">Sisa</th>
                        <th class="px-4 py-3">Terjual</th>
                        <th class="px-4 py-3">Nominal</th>
                    </tr>
                </thead>

                <tbody class="divide-y">
                    @foreach ($historiPenjualan as $item)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3">
                                {{ \Carbon\Carbon::parse($item->tanggal_pengambilan)->format('d M Y') }}
                            </td>

                            <td class="px-4 py-3">{{ $item->user->name ?? '-' }}</td>
                            <td class="px-4 py-3">{{ $item->nama_toko }}</td>

                            <td class="px-4 py-3"><ul>{!! formatVarianListWithTotal($item, '_sisa') !!}</ul></td>
                            <td class="px-4 py-3"><ul>{!! formatVarianListWithTotal($item, '_terjual') !!}</ul></td>

                            <td class="px-4 py-3 text-green-600 font-bold">
                                Rp {{ number_format($item->total_bill, 0, ',', '.') }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>


        {{-- MOBILE CARD --}}
        <div class="sm:hidden space-y-4">

            @foreach ($historiPenjualan as $item)

                <div class="bg-white border border-yellow-300 rounded-lg shadow p-4">

                    <p class="font-bold text-lg">
                        {{ \Carbon\Carbon::parse($item->tanggal_pengambilan)->format('d M Y') }}
                    </p>

                    <p><b>Sales:</b> {{ $item->user->name ?? '-' }}</p>
                    <p><b>Toko:</b> {{ $item->nama_toko }}</p>

                    <p class="mt-2 font-semibold">Sisa Roti:</p>
                    <ul>{!! formatVarianListWithTotal($item, '_sisa') !!}</ul>

                    <p class="mt-2 font-semibold">Roti Terjual:</p>
                    <ul>{!! formatVarianListWithTotal($item, '_terjual') !!}</ul>

                    <p class="mt-2 text-green-700 font-bold">
                        Total: Rp {{ number_format($item->total_bill, 0, ',', '.') }}
                    </p>

                </div>

            @endforeach

        </div>

    @endif

</div>

@endsection
