<?php

namespace App\Livewire\Admin;

use Carbon\Carbon;
use App\Models\Book;
use Livewire\Component;
use App\Models\LoanHistory;
use Livewire\Attributes\Title;
use Livewire\Attributes\Layout;

#[Title('Dashboard Admin')]
#[Layout('layouts.master')]
class Dashboard extends Component
{
    public $startDate = '';
    public $endDate = '';

    public function getStatistikProperty()
    {
        $startDate = $this->startDate ?: Carbon::now()->startOfMonth();
        $endDate = $this->endDate ?: Carbon::now()->endOfMonth();

        return [
            'total_peminjaman' => LoanHistory::whereBetween('tanggal_pinjam', [$startDate, $endDate])->count(),
            'total_pengembalian' => LoanHistory::where('status', 'dikembalikan')
                ->whereBetween('tanggal_kembali', [$startDate, $endDate])
                ->count(),
            'total_terlambat' => LoanHistory::where('status', 'terlambat')
                ->whereBetween('tanggal_pinjam', [$startDate, $endDate])
                ->count(),
            'total_denda' => LoanHistory::whereBetween('tanggal_pinjam', [$startDate, $endDate])->sum('denda'),
            'denda_terbayar' => LoanHistory::where('denda_dibayar', true)
                ->whereBetween('tanggal_pinjam', [$startDate, $endDate])
                ->sum('denda'),
            'buku_terpopuler' => $this->getBukuTerpopuler($startDate, $endDate),
        ];
    }

    private function getBukuTerpopuler($startDate, $endDate)
    {
        return LoanHistory::with('book')
            ->whereBetween('tanggal_pinjam', [$startDate, $endDate])
            ->selectRaw('id_buku, COUNT(*) as total_pinjam')
            ->groupBy('id_buku')
            ->orderBy('total_pinjam', 'desc')
            ->limit(5)
            ->get();
    }

    public function render()
    {
        // Count books currently borrowed (status: dipinjam or terlambat)
        $dipinjam = LoanHistory::whereIn('status', ['dipinjam', 'terlambat'])->count();

        // Count books returned (status: dikembalikan)
        $dikembalikan = LoanHistory::where('status', 'selesai')->count();

        // Calculate total available books stock
        $totalStok = Book::sum('stok');
        $sedangDipinjam = LoanHistory::whereIn('status', ['dipinjam', 'terlambat'])->count();
        $bukuTersedia = $totalStok - $sedangDipinjam;

        return view('livewire.admin.dashboard', [
            'dipinjam' => $dipinjam,
            'dikembalikan' => $dikembalikan,
            'bukuTersedia' => $bukuTersedia,
            'statistik' => $this->statistik,
        ]);
    }
}
