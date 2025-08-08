<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\LoanHistory;

class Receipt extends Component
{
    public $loan;

    public function mount($loanId)
    {
        $this->loan = LoanHistory::with(['user', 'book'])->findOrFail($loanId);
    }

    public function render()
    {
        return view('livewire.receipt');
    }
}