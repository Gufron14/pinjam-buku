<?php

use App\Livewire\Home;
use App\Livewire\Denda;
use App\Livewire\Auth\Login;
use App\Livewire\Auth\Profil;
use App\Livewire\Admin\Laporan;
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
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LogoutController;
use App\Http\Controllers\LaporanController;

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

Route::get('/logout', [App\Http\Controllers\LogoutController::class, 'logout'])->name('logout');

// Routes yang perlu dicek denda untuk user
Route::middleware(['denda'])->group(function () {
    Route::get('/', Home::class)->name('/');
    Route::get('daftar-buku', DaftarBuku::class)->name('daftar-buku');
    Route::get('daftar-buku/view-buku/{id}', ViewBuku::class)->name('view-buku');
});

Route::get('denda', Denda::class)->name('denda');

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

Route::post('logout', function () {
    Auth::logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect()->route('login');
})->name('logout');