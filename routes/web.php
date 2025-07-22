<?php

use App\Livewire\Home;
use App\Livewire\Auth\Login;
use App\Livewire\Auth\Profil;
use App\Livewire\Auth\Register;
use App\Livewire\Buku\ViewBuku;
use App\Livewire\Admin\Dashboard;
use App\Livewire\Buku\DaftarBuku;
use App\Livewire\RiwayatPinjaman;
use App\Livewire\Admin\KelolaBuku;
use App\Livewire\Admin\Peminjaman;
use App\Livewire\Admin\KelolaDenda;
use App\Livewire\Admin\KelolaMember;
use App\Livewire\Admin\Pengembalian;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LogoutController;
use App\Http\Controllers\LaporanController;
use App\Livewire\Admin\Laporan;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::middleware('guest')->group(function () {
    Route::get('login', Login::class)->name('login');
    Route::get('register', Register::class)->name('register');
});
Route::get('/', Home::class)->name('/');
Route::get('/logout', [App\Http\Controllers\LogoutController::class, 'logout'])->name('logout');
Route::get('daftar-buku', DaftarBuku::class)->name('daftar-buku');
Route::get('daftar-buku/view-buku/{id}', ViewBuku::class)->name('view-buku');

// User
Route::middleware('auth')->group(function () {
    Route::get('riwayat-pinjam', RiwayatPinjaman::class)->name('riwayat-pinjam');
    Route::get('profil', Profil::class)->name('profil');
});


// Admin
Route::middleware('admin')->group(function(){
    Route::get('dashboard', Dashboard::class)->name('dashboard');
    Route::get('peminjaman', Peminjaman::class)->name('peminjaman');
    Route::get('pengembalian', Pengembalian::class)->name('pengembalian');
    Route::get('kelola-buku', KelolaBuku::class)->name('kelola-buku');
    Route::get('kelola-denda', KelolaDenda::class)->name('kelola-denda');
    Route::get('laporan', Laporan::class)->name('laporan');
    Route::get('laporan/cetak', [LaporanController::class, 'cetakLaporan'])->name('laporan.cetak');
    Route::get('kelola-member', KelolaMember::class)->name('kelola-member');
});