<?php

namespace App\Livewire\Admin;

use App\Models\Book;
use Livewire\Component;
use App\Models\LoanHistory;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Livewire\Attributes\Title;
use Livewire\Attributes\Layout;

#[Title('Peminjaman Buku')]
#[Layout('layouts.master')]
class Peminjaman extends Component
{
    use WithPagination, WithFileUploads;
    public $bukti_pinjam;
    public $bukti_kembali;

    public $selectedLoanId;

    public function mount()
    {
        // Update status terlambat untuk semua peminjaman yang overdue
        $this->updateOverdueLoans();
    }

    public function updateOverdueLoans()
    {
        $activeLoans = LoanHistory::where('status', 'dipinjam')->get();
        foreach ($activeLoans as $loan) {
            $loan->checkAndUpdateOverdueStatus();
        }
    }

    public function tolakPeminjaman($loanId)
    {
        $loan = LoanHistory::find($loanId);
        if ($loan && $loan->status === 'pending') {
            $loan->update(['status' => 'ditolak']);

            session()->flash('message', 'Peminjaman berhasil ditolak.');
        }
    }

    public function setSelectedLoan($loanId)
    {
        $this->selectedLoanId = $loanId;
        $this->bukti_pinjam = null;
    }

    // Fungsi untuk mengonfirmasi peminjaman buku
    public function konfirmasiPeminjaman()
    {
        $this->validate([
            'bukti_pinjam' => 'required|image|max:2048',
        ]);

        try {
            $loan = LoanHistory::find($this->selectedLoanId);
            
            if (!$loan) {
                session()->flash('error', 'Data peminjaman tidak ditemukan.');
                return;
            }

            if ($loan->status !== 'pending') {
                session()->flash('error', 'Peminjaman ini sudah dikonfirmasi atau tidak dalam status pending.');
                return;
            }

            $book = Book::find($loan->id_buku);
            if (!$book) {
                session()->flash('error', 'Data buku tidak ditemukan.');
                return;
            }

            // Cek apakah stok masih tersedia
            if ($book->stok <= 0) {
                session()->flash('error', 'Stok buku tidak tersedia.');
                return;
            }

            // Upload bukti pinjam
            $buktiPath = $this->bukti_pinjam->store('bukti-pinjam', 'public');

            // Update status peminjaman menjadi dipinjam dan simpan bukti
            $loan->update([
                'status' => 'dipinjam',
                'tanggal_pinjam' => now(),
                'bukti_pinjam' => $buktiPath
            ]);

            // Kurangi stok buku
            $book->decrement('stok');

            session()->flash('success', 'Peminjaman berhasil dikonfirmasi. Buku sekarang dalam status dipinjam.');
            
            // Reset form
            $this->selectedLoanId = null;
            $this->bukti_pinjam = null;
            
        } catch (\Exception $e) {
            session()->flash('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    // Fungsi untuk mengonfirmasi pengembalian
public function setSelectedLoanForReturn($loanId)
{
    $this->selectedLoanId = $loanId;
    $this->bukti_kembali = null;
}

public function konfirmasiPengembalian()
{
    $this->validate([
        'bukti_kembali' => 'required|image|max:2048',
    ]);

    try {
        $loan = LoanHistory::find($this->selectedLoanId);
        
        if (!$loan) {
            session()->flash('error', 'Data peminjaman tidak ditemukan.');
            return;
        }

        if ($loan->status !== 'dikembalikan') {
            session()->flash('error', 'Buku belum dalam status dikembalikan atau sudah selesai.');
            return;
        }

        $book = Book::find($loan->id_buku);
        
        if (!$book) {
            session()->flash('error', 'Data buku tidak ditemukan.');
            return;
        }

        // Upload bukti pengembalian
        $buktiPath = $this->bukti_kembali->store('bukti-kembali', 'public');

        // Update status peminjaman menjadi selesai dan simpan bukti
        $loan->update([
            'status' => 'selesai',
            'tanggal_kembali' => now(),
            'bukti_kembali' => $buktiPath
        ]);

        // Kembalikan stok buku
        $book->increment('stok');

        session()->flash('success', 'Pengembalian berhasil dikonfirmasi. Status peminjaman sekarang selesai dan stok buku telah dikembalikan.');
        
        // Reset form
        $this->selectedLoanId = null;
        $this->bukti_kembali = null;
        
    } catch (\Exception $e) {
        session()->flash('error', 'Terjadi kesalahan: ' . $e->getMessage());
    }
}


    public function peringatiPeminjam($loanId)
    {
        // Implementasi untuk mengirim peringatan ke peminjam
        // Bisa berupa email, notifikasi, dll
        session()->flash('message', 'Peringatan berhasil dikirim ke peminjam.');
    }

    public function getStatusBadge($status)
    {
        return match ($status) {
            'pending' => '<span class="badge bg-secondary">Pending</span>',
            'dipinjam' => '<span class="badge bg-primary">Dipinjam</span>',
            'dikembalikan' => '<span class="badge bg-warning">Dikembalikan</span>',
            'terlambat' => '<span class="badge bg-danger">Terlambat</span>',
            'selesai' => '<span class="badge bg-success">Selesai</span>',
            'ditolak' => '<span class="badge bg-dark">Ditolak</span>',
            default => '<span class="badge bg-secondary">Unknown</span>',
        };
    }

    public function render()
    {
        $loans = LoanHistory::with(['user', 'book'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('livewire.admin.peminjaman', [
            'loans' => $loans,
        ]);
    }
}
