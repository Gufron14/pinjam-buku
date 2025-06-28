<?php

namespace App\Livewire\Admin;

use App\Models\Book;
use App\Models\LoanHistory;
use Livewire\Component;
use Livewire\Attributes\Title;
use Livewire\Attributes\Layout;

#[Title('Dashboard Admin')]
#[Layout('layouts.master')]
class Dashboard extends Component
{
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
            'bukuTersedia' => $bukuTersedia
        ]);
    }
}
