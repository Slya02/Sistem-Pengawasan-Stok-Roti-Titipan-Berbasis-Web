@extends('layouts.app')

@section('title', 'Sales - Input Stok Harian')

@section('content')
<div class="flex flex-col min-h-screen bg-[#fffcf0] px-6 md:px-12 py-8">

    <!-- Header -->
    <div class="bg-[#fffcf0] text-gray-900 px-6 py-3 rounded-t-xl shadow-sm flex items-center gap-2 mb-6">
        <i class="fa-solid fa-bread-slice text-lg"></i>
        <h2 class="text-lg md:text-xl font-semibold tracking-wide">Input Stok Baru</h2>
    </div>

    <form action="{{ route('sales.stok.store') }}" method="POST" class="animate-fade-in space-y-8">
        @csrf

        <!-- PILIH TOKO -->
        <div>
            <label class="block text-gray-700 font-semibold mb-2">Nama Toko</label>
            <select name="nama_toko"
                    class="w-full px-4 py-2 border-2 border-yellow-400 rounded-lg bg-[#fffcee] focus:ring-2 focus:ring-yellow-500 transition"
                    required>
                <option value="">Pilih Toko</option>
                @foreach($stores as $store)
                    <option value="{{ $store->name }}">{{ $store->name }}</option>
                @endforeach
            </select>
        </div>

        <!-- INPUT JUMLAH ROTI MASUK -->
        <div>
            <label class="block text-gray-700 font-semibold mb-2">Jumlah Roti Masuk Per Rasa</label>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @foreach($variants as $v)
                <div>
                    <label class="text-gray-700 capitalize">{{ str_replace('_', ' ', $v) }}</label>
                    <input type="number" name="{{ $v . '_masuk' }}" 
                        class="w-full px-4 py-2 border-2 border-yellow-400 rounded-lg bg-[#fffcee] transition"
                        placeholder="Jumlah masuk" required>
                </div>
                @endforeach
            </div>
        </div>

        <!-- TANGGAL -->
        <div>
            <label class="block text-gray-700 font-semibold mb-2">Tanggal Pengantaran</label>
            <input type="date" name="tanggal_pengantaran"
                class="w-full px-4 py-2 border-2 border-yellow-400 rounded-lg bg-[#fffcee] focus:ring-2 focus:ring-yellow-500 transition"
                required>
        </div>

        <!-- BUTTON -->
        <div class="flex flex-col sm:flex-row gap-3 pt-6 border-t border-yellow-200">
            <button type="submit"
                class="w-full sm:w-auto bg-yellow-400 hover:bg-yellow-500 text-gray-900 font-bold py-2 px-8 rounded-lg shadow-md transition hover:-translate-y-0.5">
                <i class="fa-solid fa-save mr-1"></i> Simpan
            </button>

            <a href="{{ route('sales.home') }}"
               class="w-full sm:w-auto text-center border-2 border-yellow-400 text-yellow-600 hover:bg-yellow-50 font-bold py-2 px-8 rounded-lg transition hover:-translate-y-0.5">
                <i class="fa-solid fa-xmark mr-1"></i> Batal
            </a>
        </div>

    </form>
</div>

<script src="https://kit.fontawesome.com/a2e0e6adf0.js" crossorigin="anonymous"></script>
<script src="{{ asset('js/input_stok.js') }}"></script>

<style>
@keyframes fade-in { 
    from {opacity:0; transform: translateY(8px);} 
    to {opacity:1; transform: translateY(0);} 
}
.animate-fade-in { animation: fade-in 0.6s ease-out; }
</style>

@endsection
