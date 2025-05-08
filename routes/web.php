<?php

use App\Livewire\Auth\Login;
use App\Livewire\Auth\Profil;
use App\Livewire\Auth\Register;
use App\Livewire\Buku\DaftarBuku;
use App\Livewire\Home;
use App\Livewire\RiwayatPinjaman;
use Illuminate\Support\Facades\Route;

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

Route::get('profil', Profil::class)->name('profil');

Route::get('/logout', [App\Http\Controllers\LogoutController::class, 'logout'])->name('logout');

Route::get('/', Home::class)->name('/');

Route::get('daftar-buku', DaftarBuku::class)->name('daftar-buku');

Route::middleware('auth')->group(function () {
    Route::get('riwayat-pinjam', RiwayatPinjaman::class)->name('riwayat-pinjam');
});
