@extends('layouts.app')

@section('title', 'Daftar Toko')

@section('content')
<div class="p-4 sm:p-8">

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800 mb-4 sm:mb-0">Daftar Toko</h1>

        <a href="{{ route('sales.tambahtoko') }}"
           class="bg-yellow-400 hover:bg-yellow-500 text-white px-4 py-2 rounded-lg shadow transition">
            + Tambah Toko
        </a>
    </div>

    {{-- Notifikasi --}}
    @if (session('success'))
        <div class="mb-4 p-4 bg-green-100 border border-green-300 text-green-800 rounded-lg">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="mb-4 p-4 bg-red-100 border border-red-300 text-red-800 rounded-lg">
            {{ session('error') }}
        </div>
    @endif


    {{-- ============================= --}}
    {{--   DESKTOP - TABLE VIEW        --}}
    {{-- ============================= --}}
    <div class="hidden sm:block bg-white rounded-lg shadow overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-100">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase text-gray-700">No</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase text-gray-700">Nama Toko</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase text-gray-700">Alamat</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase text-gray-700">No HP</th>
                    <th class="px-6 py-3 text-center text-xs font-semibold uppercase text-gray-700">Lokasi</th>
                </tr>
            </thead>

            <tbody class="divide-y divide-gray-200">
                @forelse($stores as $index => $store)

                    @php
                        $mapUrl = ($store->latitude && $store->longitude)
                            ? "https://www.google.com/maps/search/?api=1&query={$store->latitude},{$store->longitude}"
                            : null;
                    @endphp

                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4">{{ $index + 1 }}</td>
                        <td class="px-6 py-4 font-semibold">{{ $store->name }}</td>
                        <td class="px-6 py-4">{{ $store->address }}</td>
                        <td class="px-6 py-4">{{ $store->phone }}</td>

                        {{-- Tombol Lokasi --}}
                        <td class="px-6 py-4 text-center">
                            @if($mapUrl)
                                <a href="{{ $mapUrl }}" target="_blank"
                                   class="bg-yellow-400 hover:bg-yellow-500 text-white px-3 py-2 rounded-lg shadow text-sm">
                                    <i class="fa-solid fa-location-dot"></i>
                                </a>
                            @else
                                <span class="text-gray-400 text-sm">Tidak Ada</span>
                            @endif
                        </td>
                    </tr>

                @empty
                    <tr>
                        <td colspan="5" class="text-center py-4 text-gray-500">
                            Belum ada data toko.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>



    {{-- ============================= --}}
    {{--   MOBILE - CARD VIEW         --}}
    {{-- ============================= --}}
    <div class="block sm:hidden space-y-4 mt-4">
        @forelse($stores as $store)

            @php
                $mapUrl = ($store->latitude && $store->longitude)
                    ? "https://www.google.com/maps/search/?api=1&query={$store->latitude},{$store->longitude}"
                    : null;
            @endphp

            <div class="bg-white shadow rounded-lg p-4">

                {{-- Info --}}
                <div class="space-y-1 text-sm text-gray-700">
                    <p><span class="font-semibold">Nama Toko:</span> {{ $store->name }}</p>
                    <p><span class="font-semibold">Alamat:</span> {{ $store->address }}</p>
                    <p><span class="font-semibold">No HP:</span> {{ $store->phone }}</p>
                </div>

                {{-- Tombol Lokasi --}}
                <div class="mt-3">
                    @if ($mapUrl)
                        <a href="{{ $mapUrl }}" target="_blank"
                           class="block w-full bg-yellow-400 hover:bg-yellow-500 text-white py-2 rounded-lg text-center shadow">
                            <i class="fa-solid fa-location-dot mr-1"></i>
                            Lihat Lokasi
                        </a>
                    @else
                        <p class="text-center text-gray-400 text-sm">Lokasi tidak tersedia</p>
                    @endif
                </div>

            </div>

        @empty
            <p class="text-center text-gray-500">Belum ada toko ditambahkan.</p>
        @endforelse
    </div>

</div>
@endsection
