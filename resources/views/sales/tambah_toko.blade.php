@extends('layouts.app')

@section('title', 'Sales - Tambah Toko')
@section('page-title', 'Tambah Toko Mitra')

@section('content')
<div class="flex flex-col min-h-screen bg-[#fffcf0] px-6 md:px-12 py-8">

    <!-- Header -->
    <div class="bg-[#fffcf0] text-gray-900 px-6 py-3 rounded-t-xl shadow-sm flex items-center gap-2 mb-6">
        <i class="fa-solid fa-store text-lg"></i>
        <h2 class="text-lg md:text-xl font-semibold tracking-wide">Tambah Toko Mitra</h2>
    </div>

    <!-- Form -->
    <form id="formTambahToko"
      action="{{ route('sales.store') }}"
      method="POST"
      enctype="multipart/form-data"
      class="animate-fade-in w-full">

        @csrf

        <!-- Row 1 -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <div>
                <label class="block text-gray-700 font-semibold mb-2">Nama Toko</label>
                <input type="text" name="name" class="w-full px-4 py-2 border-2 border-yellow-400 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:outline-none transition bg-[#fffcee]" placeholder="Masukkan nama toko" required>
            </div>
            <div>
                <label class="block text-gray-700 font-semibold mb-2">No HP / WhatsApp</label>
                <input type="number" name="phone" class="w-full px-4 py-2 border-2 border-yellow-400 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:outline-none transition bg-[#fffcee]" placeholder="Masukkan nomor HP" required>
            </div>
        </div>

        <!-- Row 2 -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <div>
                <label class="block text-gray-700 font-semibold mb-2">Nama Pemilik</label>
                <input type="text" name="owner_name" class="w-full px-4 py-2 border-2 border-yellow-400 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:outline-none transition bg-[#fffcee]" placeholder="Masukkan nama pemilik" required>
            </div>
            <div>
                <label class="block text-gray-700 font-semibold mb-2">Alamat Lengkap</label>
                <input type="text" name="address" id="address" class="w-full px-4 py-2 border-2 border-yellow-400 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:outline-none transition bg-[#fffcee]" placeholder="Masukkan alamat lengkap" required>
            </div>
        </div>

        <!-- Row 3 -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <div>
                <label class="block text-gray-700 font-semibold mb-2">Tanggal Bergabung</label>
                <input type="date" name="join_date" class="w-full px-4 py-2 border-2 border-yellow-400 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:outline-none transition bg-[#fffcee]" required>
            </div>

            <!-- Lokasi -->
            <div>
                <label class="block text-gray-700 font-semibold mb-2">Lokasi Toko</label>
                <div class="flex flex-wrap md:flex-nowrap items-center gap-2">
                    <button type="button" id="btnMap"
                        class="bg-yellow-400 hover:bg-yellow-500 text-gray-900 font-semibold py-2 px-4 rounded-lg shadow-sm hover:shadow-md transition transform hover:-translate-y-0.5 w-full md:w-auto">
                        <i class="fa-solid fa-location-dot mr-1"></i> Pilih dari Peta
                    </button>
                    <input type="text" name="latitude" id="latitude" placeholder="Lat"
                        class="w-full md:w-24 px-3 py-2 border-2 border-yellow-300 rounded-lg text-sm focus:ring-1 focus:ring-yellow-400 focus:outline-none bg-[#fffcee]"
                        readonly>
                    <input type="text" name="longitude" id="longitude" placeholder="Lng"
                        class="w-full md:w-24 px-3 py-2 border-2 border-yellow-300 rounded-lg text-sm focus:ring-1 focus:ring-yellow-400 focus:outline-none bg-[#fffcee]"
                        readonly>
                </div>
            </div>
        </div>

        <!-- Upload Foto -->
        <div class="mb-8">
            <label class="block text-gray-700 font-semibold mb-2">Foto Toko</label>
            <label for="photo" id="upload-area"
                class="border-2 border-dashed border-yellow-400 rounded-lg p-8 text-center bg-yellow-50 hover:bg-yellow-100 cursor-pointer transition block">
                <div id="upload-content" class="flex flex-col items-center justify-center transition-all duration-300">
                    <svg class="w-12 h-12 text-yellow-500 mb-2 animate-bounce" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    <p class="text-gray-700 mb-1 font-medium">Drag dan drop atau klik untuk upload</p>
                    <p class="text-gray-400 text-sm">Maksimum size: 5 MB per foto</p>
                </div>
            </label>

            <input type="file" id="photo" name="photo[]" multiple class="hidden" accept="image/*">
            <div id="preview-container" class="grid grid-cols-3 gap-4 mt-4"></div>
        </div>

        <!-- Tombol -->
        <div class="flex gap-4">
            <button type="submit" class="flex-1 bg-yellow-400 hover:bg-yellow-500 text-gray-900 font-bold py-2 px-4 rounded-lg shadow-md hover:shadow-lg transition transform hover:-translate-y-0.5">
                <i class="fa-solid fa-save mr-1"></i> Simpan
            </button>
            <button type="reset" class="border-2 border-yellow-400 text-yellow-600 hover:bg-yellow-50 font-bold py-2 px-6 rounded-lg transition transform hover:-translate-y-0.5 hover:shadow-sm">
                <i class="fa-solid fa-eraser mr-1"></i> Hapus
            </button>
        </div>
    </form>
</div>

<!-- Modal Peta -->
<div id="mapModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-[9999]">
  <div class="bg-white rounded-xl p-4 shadow-xl w-11/12 md:w-2/3 lg:w-1/2 relative animate-fade-in-up">
    <button id="closeMap" class="absolute top-2 right-3 text-gray-500 hover:text-gray-800 text-xl transition transform hover:scale-110">&times;</button>
    <h2 class="text-lg font-bold mb-3 text-yellow-600 flex items-center gap-2"><i class="fa-solid fa-map-location-dot"></i> Pilih Lokasi Toko</h2>
    <div id="map" style="height: 400px;" class="rounded-lg border border-yellow-300"></div>
    <div class="flex justify-end mt-4">
      <button id="saveMap" class="bg-yellow-400 hover:bg-yellow-500 text-gray-900 px-4 py-2 rounded-lg shadow-sm hover:shadow-md transition transform hover:-translate-y-0.5">
        Simpan Lokasi
      </button>
    </div>
  </div>
</div>

<style>
@keyframes fade-in { from {opacity:0; transform: translateY(5px);} to {opacity:1; transform: translateY(0);} }
@keyframes fade-in-up { from {opacity:0; transform: translateY(10px);} to {opacity:1; transform: translateY(0);} }
.animate-fade-in { animation: fade-in 0.6s ease-out; }
.animate-fade-in-up { animation: fade-in-up 0.5s ease-out; }
#mapModal.flex { display: flex !important; }
</style>

@endsection

@section('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.3/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.3/dist/leaflet.js"></script>
<script src="{{ asset('js/tambah_toko.js') }}"></script>
@endsection
