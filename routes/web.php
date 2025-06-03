<?php

use App\Http\Controllers\PenggunaController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\Customer\LoginController as CustomerLoginController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\MenuItemController;
use App\Http\Controllers\DashboardController;

//user
use App\Http\Controllers\Customer\TransactionController as CustomerTransactionController; // Alias untuk menghindari konflik nama jika ada TransactionController lain
use App\Http\Controllers\TransaksiController; // Ini adalah controller untuk admin

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::group(['middleware' => 'admin', 'prefix' => 'admin'], function () {
    Route::get('/', [DashboardController::class, 'index'])->name('home');
    // ... (rute menu dan menu-items Anda yang sudah ada) ...
    Route::get('menu', [MenuController::class, 'index'])->name('menu.index');
    Route::get('menu/create', [MenuController::class, 'create'])->name('menu.create');
    Route::post('menu', [MenuController::class, 'store'])->name('menu.store');
    Route::get('menu/{id}/edit', [MenuController::class, 'edit'])->name('menu.edit');
    Route::patch('menu/{id}', [MenuController::class, 'update'])->name('menu.update');
    Route::delete('menu/{id}', [MenuController::class, 'destroy'])->name('menu.destroy');

    Route::get('menu-items', [MenuItemController::class, 'index'])->name('menu-item.index');
    Route::get('menu-items/create', [MenuItemController::class, 'create'])->name('menu-item.create');
    Route::post('menu-items', [MenuItemController::class, 'store'])->name('menu-item.store');
    Route::get('menu-items/{id}/edit', [MenuItemController::class, 'edit'])->name('menu-item.edit');
    Route::patch('menu-items/{id}', [MenuItemController::class, 'update'])->name('menu-item.update');
    Route::delete('menu-items/{id}', [MenuItemController::class, 'destroy'])->name('menu-item.destroy');


    Route::get('transaksi/booking', [TransaksiController::class, 'index'])->name('booking.user');
    Route::post('transaksi/booking', [TransaksiController::class, 'storeAccept'])->name('booking.storeAccept'); // Mungkin untuk aksi terima spesifik
    Route::post('transaksi/booking/reject', [TransaksiController::class, 'storeReject'])->name('booking.storeReject'); // Mungkin untuk aksi tolak spesifik

    // TAMBAHKAN ROUTE INI:
    Route::post('transaksi/update-status', [TransaksiController::class, 'updateStatus'])->name('admin.transaksi.updateStatus');
    Route::delete('/transaksi/{transaksi_id_212102}/destroy', [TransaksiController::class, 'destroy'])->name('transaksi.destroy');

    Route::get('historyAdmin', [TransaksiController::class, 'history'])->name('historyAdmin');
    Route::get('/admin/transaksi/history', [TransaksiController::class, 'history'])->name('admin.transaksi.history');
    Route::get('/transaksi/{transaksi_id_212102}/show', [TransaksiController::class, 'show'])->name('admin.transaksi.show');

    // ... (rute pengguna Anda yang sudah ada) ...
    Route::get('pengguna', [PenggunaController::class, 'index'])->name('pengguna.index');
    Route::get('pengguna/create', [PenggunaController::class, 'create'])->name('pengguna.create');
    Route::post('pengguna', [PenggunaController::class, 'store'])->name('pengguna.store');
    Route::get('pengguna/{id}/edit', [PenggunaController::class, 'edit'])->name('pengguna.edit');
    Route::patch('pengguna/{id}', [PenggunaController::class, 'update'])->name('pengguna.update');
    Route::delete('pengguna/{id}', [PenggunaController::class, 'destroy'])->name('pengguna.destroy');


    Route::get('logoutaksi', [LoginController::class, 'logoutaksi'])->name('logoutaksi');
});


Route::group(['middleware' => 'customer', 'prefix' => 'customer'], function () {
    // Route::get('/', function () {
    // return 'customer';
    // })->name('customer');

    Route::get('item', [CustomerTransactionController::class, 'transaksi'])->name('item');
    Route::get('transaksi', [CustomerTransactionController::class, 'index'])->name('customer');
    Route::post('transaksi', [CustomerTransactionController::class, 'store'])->name('transaction.store');
    Route::get('booking', [CustomerTransactionController::class, 'booking'])->name('booking'); // Ini nama route-nya 'booking', di Blade Anda juga 'Booking'
    Route::get('history', [CustomerTransactionController::class, 'history'])->name('history');
    Route::get('/admin/transaksi/history', [CustomerTransactionController::class, 'history'])->name('customer.transaksi.history');
    Route::get('/transaksi/{transaksi_id_212102}/detail', [CustomerTransactionController::class, 'detail'])->name('customer.transaksi.detail');

    Route::get('logoutaksi', [CustomerLoginController::class, 'logoutaksi'])->name('logoutaksicustomer');
});

Route::get('/', [LoginController::class, 'login'])->name('login');
Route::post('register', [LoginController::class, 'register'])->name('register');
Route::get('forgot', [LoginController::class, 'viewForgot'])->name('forgot');
Route::post('forgot', [LoginController::class, 'forgot'])->name('forgotAksi');
Route::post('loginaksi', [LoginController::class, 'loginaksi'])->name('loginaksi');
Route::get('view-verify', [LoginController::class, 'token'])->name('view-verify');
Route::get('verify-token', [LoginController::class, 'verifyToken'])->name('verify-token');
Route::post('verify-token', [LoginController::class, 'updatePassword'])->name('verify-token');

// Perhatikan bahwa route 'detail' ini menggunakan TransactionController dari namespace customer
// Jika ini dimaksudkan untuk admin, pastikan controller dan middleware-nya sesuai.
// Jika untuk customer, pastikan ID transaksi tidak bisa diakses sembarangan.
Route::get('booking/{id}/detail', [CustomerTransactionController::class, 'detail'])->name('detail');
