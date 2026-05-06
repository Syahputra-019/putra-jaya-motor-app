<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\KomplainController;
use App\Http\Controllers\LandingPageController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\MekanikController;
use App\Http\Controllers\PelangganController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\SparepartController;
use App\Http\Controllers\TransaksiController;
use Illuminate\Support\Facades\Route;

// 1. UBAH rute '/' yang tadinya redirect ke login, jadi ke Landing Page
Route::get('/', [LandingPageController::class, 'index'])->name('landing');

// 2. TAMBAH rute untuk proses simpan booking dari form landing page
Route::post('/booking/store-public', [LandingPageController::class, 'storeBooking'])->name('booking.public');
Route::get('/transaksi/{id}/nota', [TransaksiController::class, 'notaPublik'])->name('transaksi.nota')->middleware('signed');

Route::get('login', [AuthController::class, 'showLogin'])->name('login');
Route::post('login', [AuthController::class, 'login'])->name('login.post');
Route::post('logout', [AuthController::class, 'logout'])->name('logout');
Route::get('register', [AuthController::class, 'showRegister'])->name('register');
Route::post('register', [AuthController::class, 'register'])->name('register.post');

Route::middleware(['auth'])->group(function () {

    Route::middleware(['role:admin'])->group(function () {
        Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');
        Route::get('/dashboard/cetak', [DashboardController::class, 'cetak'])->name('dashboard.cetak');
        Route::get('/dashboard/excel', [DashboardController::class, 'exportExcel'])->name('dashboard.excel');
        Route::resource('/sparepart', SparepartController::class);
        Route::resource('/mekanik', MekanikController::class);
        Route::resource('/pelanggan', PelangganController::class);
        Route::resource('/service', ServiceController::class);
        Route::resource('/transaksi', TransaksiController::class);
        Route::get('/transaksi/{id}/cetak', [TransaksiController::class, 'cetak'])->name('transaksi.cetak');
        Route::get('/transaksi/{id}/bayar', [TransaksiController::class, 'bayar'])->name('transaksi.bayar');
        Route::post('/transaksi/{id}/upload-struk', [TransaksiController::class, 'uploadStruk'])->name('transaksi.uploadStruk');
        Route::post('/transaksi/{id}/konfirmasi', [TransaksiController::class, 'konfirmasiPembayaran'])->name('transaksi.konfirmasi');
        Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan.index');
        Route::get('/data-komplain', [KomplainController::class, 'adminIndex'])->name('pelanggan.komplain.index');
        Route::post('/komplain/{id}/tanggapi', [KomplainController::class, 'tanggapi'])->name('komplain.tanggapi');
    });

    Route::middleware(['role:mekanik'])->group(function () {
        Route::get('/jadwal-mekanik', [MekanikController::class, 'jadwalKerja'])->name('mekanik.jadwal');
        Route::put('/mekanik/jadwal/{booking}/update', [MekanikController::class, 'updateStatus'])->name('mekanik.jadwal.update');
    });

    Route::middleware(['role:pelanggan'])->group(function () {
        Route::resource('/booking', BookingController::class);
        Route::get('/my-booking', [BookingController::class, 'myBooking'])->name('booking.mine');
        Route::resource('komplain', KomplainController::class);
    });

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile/info', [ProfileController::class, 'updateInfo'])->name('profile.updateInfo'); // Ganti ini
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.updatePassword');
});

Route::post('/midtrans/callback', [TransaksiController::class, 'callback']);
