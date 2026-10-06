<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PinjamController;
use App\Http\Controllers\Admin\BukuController;
use App\Http\Controllers\Member\TempController;
use App\Http\Controllers\Admin\KategoriController;
use App\Http\Controllers\Member\MemberController;
use App\Http\Controllers\Member\BookingController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\BookingController as AdminBookingController;

// ==================== MEMBER / PUBLIC ====================
Route::get('/', [MemberController::class, 'index'])->name('member.index')->middleware('isMember');
Route::get('detail-buku/{buku}', [MemberController::class, 'detailBuku'])->name('member.detailBuku')->middleware('isMember');

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/register-member', [AuthController::class, 'registerMember'])->name('registerMember');
    Route::post('/login-member', [AuthController::class, 'loginMember'])->name('loginMember');
});

Route::middleware('auth')->group(function () {
    Route::get('/logout', [AuthController::class, 'logout'])->name('logout');
});

// ==================== ADMIN ====================
Route::middleware(['auth', 'isAdmin'])->group(function () {
    // Dashboard & Profil Admin
    Route::prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        Route::get('/profil', [DashboardController::class, 'tampilProfil'])->name('profil');
        Route::put('/profil', [DashboardController::class, 'updateProfil']);
        Route::get('/ganti-password', [DashboardController::class, 'tampilGantiPassword'])->name('ganti-password');
        Route::post('/ganti-password', [DashboardController::class, 'updateGantiPassword'])->name('ganti-password');
    });

    // Data Master
    Route::prefix('admin/master')->name('admin.master.')->group(function () {
        Route::resource('user', UserController::class);
        Route::put('user/reset-password/{user}', [UserController::class, 'resetPassword'])->name('user.resetPassword');
        Route::resource('kategori', KategoriController::class);
        Route::resource('buku', BukuController::class);
    });

    // ==================== TRANSAKSI ====================
    Route::prefix('admin/transaksi')->name('admin.transaksi.')->group(function () {
        // Booking (resource lengkap: index, show, destroy)
        Route::resource('booking', AdminBookingController::class)->except(['create', 'store', 'edit', 'update']);

        // Peminjaman
        Route::get('peminjaman', [PinjamController::class, 'index'])->name('peminjaman.index');
        Route::post('peminjaman/store-single', [PinjamController::class, 'storeSingle'])->name('peminjaman.storeSingle');
        Route::post('peminjaman/store-bulk', [PinjamController::class, 'store'])->name('peminjaman.storeBulk');

        // Pengembalian
        Route::get('pengembalian', [PinjamController::class, 'pengembalian_index'])->name('peminjaman.pengembalian');

        // DataTables & Export
        Route::get('peminjaman/data', [PinjamController::class, 'getData'])->name('peminjaman.data');
        Route::get('print-pinjam', [PinjamController::class, 'printPinjam'])->name('pinjam.printPinjam');
        Route::get('export-csv-pinjam', [PinjamController::class, 'exportCsvPinjam'])->name('pinjam.exportCsvPinjam');

        // Kembalikan Buku
        Route::put('pinjam/kembalikanBuku/{no_pinjam}/{id_buku}', [PinjamController::class, 'kembalikanBuku'])->name('pinjam.kembalikanBuku');

        Route::post('booking/{id}/cancel', [AdminBookingController::class, 'cancel'])->name('booking.cancel');
    Route::post('booking/bulk-delete', [AdminBookingController::class, 'bulkDelete'])->name('booking.bulkDelete');
    Route::post('booking/cleanup-expired', [AdminBookingController::class, 'cleanupExpired'])->name('booking.cleanupExpired');
    });
});

// ==================== MEMBER (AUTH) ====================
Route::middleware(['auth', 'isMember'])->group(function () {
    Route::prefix('member')->name('member.')->group(function () {
        Route::get('/profil', [MemberController::class, 'tampilProfil'])->name('profil');
        Route::put('/profil', [MemberController::class, 'updateProfil']);
        Route::get('/ganti-password', [MemberController::class, 'tampilGantiPassword'])->name('ganti-password');
        Route::put('/ganti-password', [MemberController::class, 'updateGantiPassword'])->name('ganti-password');
        Route::post('tambah-ke-keranjang', [TempController::class, 'tambahKeranjang'])->name('tambahKeranjang');
        Route::get('data-keranjang/{user}', [TempController::class, 'dataKeranjang'])->name('dataKeranjang');
        Route::delete('hapus-keranjang/{buku}/{user}', [TempController::class, 'hapusKeranjang'])->name('hapusKeranjang');
        Route::post('simpan-booking', [TempController::class, 'simpanBooking'])->name('simpanBooking');
        Route::get('data-booking/{user}', [BookingController::class, 'dataBooking'])->name('dataBooking');
        Route::get('booking-pdf/{user}', [BookingController::class, 'bookingPdf'])->name('bookingPdf');
        Route::get('sedang-pinjam/{user}', [PinjamController::class, 'sedangPinjam'])->name('sedangPinjam');
        Route::get('riwayat-pinjam/{user}', [PinjamController::class, 'riwayatPinjam'])->name('riwayatPinjam');
    });
});