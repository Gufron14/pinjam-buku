<?php

namespace App\Livewire;

use App\Models\LoanHistory;
use App\Models\Book;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Livewire\Attributes\Title;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

#[Title('Riwayat Pinjaman')]
class RiwayatPinjaman extends Component
{
    use WithPagination, WithFileUploads;

    public $selectedLoanId;
    public $showPaymentModal = false;
    public $paymentProof;
    public $canBorrowBooks = true;

    public function mount()
    {
        // Check if user can borrow books (no unpaid fines)
         $this->canBorrowBooks = !LoanHistory::hasUnpaidFines(Auth::id());
    }

    public function returnBook($loanId)
    {
        $loan = LoanHistory::find($loanId);
        
        if (!$loan || $loan->id_user != Auth::id()) {
            session()->flash('error', 'Transaksi peminjaman tidak ditemukan.');
            return;
        }

        // Check if the loan is overdue
        $loan->checkAndUpdateOverdueStatus();
        
        // If there's a fine that hasn't been paid, don't allow return
        if ($loan->status === 'terlambat' && $loan->denda > 0 && !$loan->denda_dibayar) {
            session()->flash('error', 'Anda memiliki denda yang belum dibayar. Silakan bayar denda terlebih dahulu.');
            return;
        }

        // Mark as returned
        $loan->update([
            'tanggal_kembali' => now(),
            'status' => 'dikembalikan',
        ]);

        // Increase book stock
        $book = Book::find($loan->id_buku);
        $book->stok += 1;
        $book->save();

        session()->flash('message', 'Buku berhasil dikembalikan.');
        
        // Refresh the canBorrowBooks status
        $this->canBorrowBooks = !LoanHistory::hasUnpaidFines(Auth::id());
    }

    public function openPaymentModal($loanId)
    {
        $this->selectedLoanId = $loanId;
        $this->showPaymentModal = true;
    }

    public function closePaymentModal()
    {
        $this->showPaymentModal = false;
        $this->paymentProof = null;
        $this->selectedLoanId = null;
    }

    public function uploadPaymentProof()
    {
        $this->validate([
            'paymentProof' => 'required|image|max:1024', // max 1MB
        ]);

        $loan = LoanHistory::find($this->selectedLoanId);
        
        if (!$loan || $loan->id_user != Auth::id()) {
            session()->flash('error', 'Transaksi peminjaman tidak ditemukan.');
            $this->closePaymentModal();
            return;
        }

        // Store the payment proof
        $path = $this->paymentProof->store('payment-proofs', 'public');
        
        // Update the loan record
        $loan->update([
            'bukti_pembayaran' => $path,
            'denda_dibayar' => true,
            'konfirmasi_admin' => false, // Admin still needs to confirm
        ]);

        session()->flash('message', 'Bukti pembayaran berhasil diunggah. Menunggu konfirmasi admin.');
        $this->closePaymentModal();
    }

    // Admin function to confirm payment
    public function confirmPayment($loanId)
    {
        if (!Auth::user()->hasRole('admin')) {
            return;
        }

        $loan = LoanHistory::find($loanId);
        
        if (!$loan) {
            session()->flash('error', 'Transaksi peminjaman tidak ditemukan.');
            return;
        }

        $loan->update([
            'konfirmasi_admin' => true,
        ]);

        session()->flash('message', 'Pembayaran denda telah dikonfirmasi.');
    }

    public function render()
    {
        // Get all loan histories for the current user
        $loanHistories = LoanHistory::with('book')
            ->where('id_user', Auth::id())
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        
        // Check and update status for all loans
        foreach ($loanHistories as $loan) {
            if ($loan->status === 'dipinjam') {
                $loan->checkAndUpdateOverdueStatus();
            }
        }
        
        // Refresh the canBorrowBooks status
        $this->canBorrowBooks = !LoanHistory::hasUnpaidFines(Auth::id());
        
        return view('livewire.riwayat-pinjaman', [
            'loanHistories' => $loanHistories,
        ]);
    }
}
