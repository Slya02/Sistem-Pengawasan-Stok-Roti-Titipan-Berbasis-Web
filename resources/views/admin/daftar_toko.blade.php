@extends('layouts.admin')

@section('title', 'Daftar Toko')

@section('content')
<div class="p-4 sm:p-8">

    <h1 class="text-2xl font-bold text-gray-800 mb-6">Daftar Toko</h1>

    {{-- Notifikasi --}}
    @if (session('success'))
        <div class="mb-4 p-4 bg-green-100 text-green-800 border border-green-300 rounded-lg">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="mb-4 p-4 bg-red-100 text-red-800 border border-red-300 rounded-lg">
            {{ session('error') }}
        </div>
    @endif


    {{-- =============================
        DESKTOP TABLE
    ============================= --}}
    <div class="hidden sm:block bg-white shadow rounded-lg overflow-x-auto mb-6">
        <table class="min-w-full divide-y divide-gray-200">
            
            {{-- HEADER KUNING --}}
            <thead class="bg-yellow-400 text-black">
                <tr>
                    <th class="px-6 py-3 text-left text-xs uppercase font-semibold">No</th>
                    <th class="px-6 py-3 text-left text-xs uppercase font-semibold">Nama Toko</th>
                    <th class="px-6 py-3 text-left text-xs uppercase font-semibold">Alamat</th>
                    <th class="px-6 py-3 text-left text-xs uppercase font-semibold">No HP</th>
                    <th class="px-6 py-3 text-left text-xs uppercase font-semibold">Aksi</th>
                </tr>
            </thead>

            <tbody class="divide-y divide-gray-200">

                @foreach ($stores as $index => $store)
                    @php
                        $mapUrl = ($store->latitude && $store->longitude)
                            ? "https://www.google.com/maps/search/?api=1&query={$store->latitude},{$store->longitude}"
                            : null;
                    @endphp

                    <tr>
                        <td class="px-6 py-4">{{ $index + 1 }}</td>
                        <td class="px-6 py-4 font-semibold">{{ $store->name }}</td>
                        <td class="px-6 py-4">{{ $store->address }}</td>
                        <td class="px-6 py-4">{{ $store->phone }}</td>

                        <td class="px-6 py-4">
                            <div class="flex gap-2 items-center">

                                {{-- Tombol Lokasi --}}
                                @if($mapUrl)
                                    <a href="{{ $mapUrl }}" target="_blank"
                                       class="bg-yellow-400 hover:bg-yellow-500 text-white px-3 py-2 rounded-md shadow">
                                        <i class="fa-solid fa-location-dot"></i>
                                    </a>
                                @endif

                                {{-- Tombol Edit --}}
                                <button onclick="openEditModal(
                                        '{{ $store->id }}',
                                        '{{ $store->name }}',
                                        '{{ $store->address }}',
                                        '{{ $store->phone }}'
                                    )"
                                    class="px-3 py-2 bg-blue-500 hover:bg-blue-600 text-white rounded-md shadow">
                                    <i class="fa-solid fa-pen"></i>
                                </button>

                                {{-- Tombol Hapus --}}
                                <form action="{{ route('admin.toko.destroy', $store->id) }}"
                                      method="POST"
                                      onsubmit="return confirm('Yakin ingin menghapus toko ini?')">
                                    @csrf
                                    @method('DELETE')

                                    <button class="px-3 py-2 bg-red-500 hover:bg-red-600 text-white rounded-md shadow">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>

                    </tr>
                @endforeach

            </tbody>
        </table>
    </div>


    {{-- =============================
        MOBILE CARD VIEW
    ============================= --}}
    <div class="block sm:hidden space-y-4">

        @foreach ($stores as $store)

            @php
                $mapUrl = ($store->latitude && $store->longitude)
                    ? "https://www.google.com/maps/search/?api=1&query={$store->latitude},{$store->longitude}"
                    : null;
            @endphp

            <div class="bg-white rounded-lg shadow p-4">

                {{-- Informasi --}}
                <p><b>Nama:</b> {{ $store->name }}</p>
                <p><b>Alamat:</b> {{ $store->address }}</p>
                <p><b>No HP:</b> {{ $store->phone }}</p>

                {{-- Tombol Lokasi --}}
                @if($mapUrl)
                    <a href="{{ $mapUrl }}" target="_blank"
                       class="mt-3 block bg-yellow-400 hover:bg-yellow-500 text-white text-center py-2 rounded-lg shadow">
                        <i class="fa-solid fa-location-dot mr-1"></i>
                        Lihat Lokasi
                    </a>
                @endif

                {{-- Tombol Edit & Hapus --}}
                <div class="flex gap-2 mt-3">
                    <button onclick="openEditModal(
                            '{{ $store->id }}',
                            '{{ $store->name }}',
                            '{{ $store->address }}',
                            '{{ $store->phone }}'
                        )"
                        class="w-1/2 bg-blue-500 hover:bg-blue-600 text-white py-2 rounded-lg">
                        Edit
                    </button>

                    <form action="{{ route('admin.toko.destroy', $store->id) }}"
                          method="POST" class="w-1/2"
                          onsubmit="return confirm('Hapus toko ini?')">
                        @csrf
                        @method('DELETE')

                        <button class="w-full bg-red-500 hover:bg-red-600 text-white py-2 rounded-lg">
                            Hapus
                        </button>
                    </form>

                </div>

            </div>

        @endforeach

    </div>

</div>


{{-- =============================
        MODAL EDIT
============================= --}}
<div id="editModal"
     class="fixed inset-0 bg-black/40 hidden justify-center items-center p-4 z-[999]">

    <div class="bg-white w-full max-w-md rounded-lg shadow-lg p-5 relative">

        <button onclick="closeEditModal()"
            class="absolute right-3 top-3 text-gray-600 hover:text-black">
            ✖
        </button>

        <h2 class="text-xl font-bold mb-4">Edit Toko</h2>

        <form id="editForm" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label class="font-semibold">Nama Toko</label>
                <input id="edit_name" name="name" class="w-full border rounded px-3 py-2">
            </div>

            <div class="mb-3">
                <label class="font-semibold">Alamat</label>
                <textarea id="edit_address" name="address"
                          class="w-full border rounded px-3 py-2"></textarea>
            </div>

            <div class="mb-3">
                <label class="font-semibold">No HP</label>
                <input id="edit_phone" name="phone" class="w-full border rounded px-3 py-2">
            </div>

            <button class="bg-yellow-400 hover:bg-yellow-500 text-white px-4 py-2 rounded w-full">
                Simpan Perubahan
            </button>
        </form>
    </div>
</div>


<script>
function openEditModal(id, name, address, phone) {
    document.getElementById('edit_name').value = name;
    document.getElementById('edit_address').value = address;
    document.getElementById('edit_phone').value = phone;

    document.getElementById('editForm').action = "/admin/toko/" + id;

    document.getElementById('editModal').classList.remove('hidden');
    document.getElementById('editModal').classList.add('flex');
}

function closeEditModal() {
    document.getElementById('editModal').classList.add('hidden');
    document.getElementById('editModal').classList.remove('flex');
}
</script>

@endsection
