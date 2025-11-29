<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\SalesController;
use App\Http\Controllers\StoreController;
use App\Http\Controllers\SalesSisaController;
use App\Http\Controllers\AdminController;

/*
|--------------------------------------------------------------------------
| Auth Routes
|--------------------------------------------------------------------------
*/
Route::get('/', fn () => view('welcome'));

Route::get('/login',  [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.post');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        Route::get('/home', [AdminController::class, 'home'])->name('home');

        // SALES
        Route::get('/sales', [AdminController::class, 'sales'])->name('sales');
        Route::post('/sales/tambah', [AdminController::class, 'tambahSales'])->name('sales.tambah');
        Route::put('/sales/{id}', [AdminController::class, 'updateSales'])->name('sales.update');
        Route::delete('/sales/{id}', [AdminController::class, 'hapusSales'])->name('sales.hapus');
        
        // TARGET
        Route::get('/target', [AdminController::class, 'target'])->name('target');
        Route::post('/target/update', [AdminController::class, 'updateTarget'])->name('target.update');


        // TOKO
        Route::get('/daftar-toko', [AdminController::class, 'daftarToko'])->name('daftartoko');
        Route::put('/toko/{id}', [AdminController::class, 'updateToko'])->name('toko.update');
        Route::delete('/toko/{id}', [AdminController::class, 'hapusToko'])->name('toko.destroy');

        // LOKASI & HISTORI
        Route::get('/lokasi-toko', [AdminController::class, 'lokasiToko'])->name('lokasitoko');
        Route::get('/histori', [AdminController::class, 'histori'])->name('histori');

        // 📊 ➜ STATISTIK BARU
        Route::get('/statistik', [AdminController::class, 'statistik'])->name('statistik');
    });

/*
|--------------------------------------------------------------------------
| Sales Routes
|--------------------------------------------------------------------------
*/
/*
|--------------------------------------------------------------------------
| Sales Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:sales'])
    ->prefix('sales')
    ->name('sales.')
    ->group(function () {

        Route::get('/home', [SalesController::class, 'home'])->name('home');
        Route::get('/histori', [SalesController::class, 'histori'])->name('histori');
        Route::get('/lokasi', [SalesController::class, 'lokasi'])->name('lokasi');

        // INPUT STOK ROTI
        Route::get('/input-stok', [SalesController::class, 'create'])->name('input');
        Route::post('/input-stok', [SalesController::class, 'storeStok'])->name('stok.store');

        // INPUT SISA ROTI
        Route::get('/input-sisa', [SalesSisaController::class, 'create'])->name('sisa.create');
        Route::post('/input-sisa', [SalesSisaController::class, 'store'])->name('sisa.store');

        // CRUD TOKO
        Route::get('/daftar-toko', [StoreController::class, 'index'])->name('daftartoko');

        // 🔥 Perbaikan utama (GET dulu, baru POST)
        Route::get('/tambah-toko', [StoreController::class, 'create'])->name('tambahtoko');
        Route::post('/tambah-toko', [StoreController::class, 'store'])->name('store');

        Route::delete('/toko/{id}', [StoreController::class, 'destroy'])->name('toko.destroy');
    });

