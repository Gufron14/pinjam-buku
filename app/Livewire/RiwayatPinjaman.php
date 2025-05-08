<?php

namespace App\Livewire;

use App\Models\LoanHistory;
use Livewire\Component;
use Livewire\Attributes\Title;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;

#[Title('Riwayat Peminjaman Buku')]

class RiwayatPinjaman extends Component
{
    use WithPagination;
    
    protected $paginationTheme = 'bootstrap';

    public function render()
    {
        $userId = Auth::id();
        
        // If user is logged in, show their loan history
        // Otherwise, show all loan histories (for demo purposes)
        $query = LoanHistory::with(['book', 'book.category']);
        
        if ($userId) {
            $query->where('id_user', $userId);
        }
        
        $loanHistories = $query->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('livewire.riwayat-pinjaman', [
            'loanHistories' => $loanHistories
        ]);
    }
}
