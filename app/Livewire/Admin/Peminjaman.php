<?php

namespace App\Livewire\Admin;

use App\Models\Book;
use Livewire\Component;
use App\Models\LoanHistory;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Livewire\Attributes\Title;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\Mail;
use App\Mail\PeminjamanKonfirmasi;
use App\Mail\PengembalianKonfirmasi;
use App\Mail\PeminjamanDitolak;

#[Title('Peminjaman Buku')]
#[Layout('layouts.master')]
class Peminjaman extends Component
{
    use WithPagination;
    // public $bukti_pinjam;
    // public $bukti_kembali;

    public $selectedLoanId;
    public $search = '';
    public $filterStatus = '';
    public $filterTanggalAwal = '';
    public $filterTanggalAkhir = '';
    
    // Loading states
    public $loadingKonfirmasiPeminjaman = false;
    public $loadingKonfirmasiPengembalian = false;
    public $loadingTolakPeminjaman = false;

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
        $this->loadingTolakPeminjaman = true;
        
        try {
            $loan = LoanHistory::with(['user', 'book'])->find($loanId);
            
            if ($loan && $loan->status === 'pending') {
                $loan->update(['status' => 'ditolak']);

                // Kirim email notifikasi ke user
                Mail::to($loan->user->email)->send(new PeminjamanDitolak($loan));

                session()->flash('success', 'Peminjaman berhasil ditolak dan notifikasi email telah dikirim.');
            } else {
                session()->flash('error', 'Data peminjaman tidak ditemukan atau status tidak valid.');
            }
        } catch (\Exception $e) {
            session()->flash('error', 'Terjadi kesalahan: ' . $e->getMessage());
        } finally {
            $this->loadingTolakPeminjaman = false;
        }
    }

    public function setSelectedLoan($loanId)
    {
        $this->selectedLoanId = $loanId;
        // $this->bukti_pinjam = null;
    }

    // Fungsi untuk mengonfirmasi peminjaman buku
    public function konfirmasiPeminjaman($loanId)
    {
        $this->loadingKonfirmasiPeminjaman = true;
        
        try {
            $loan = LoanHistory::with(['user', 'book'])->find($loanId);

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

            // Update status peminjaman menjadi dipinjam
            $loan->update([
                'status' => 'dipinjam',
                'tanggal_pinjam' => now(),
            ]);

            // Kurangi stok buku
            $book->decrement('stok');

            // Kirim email notifikasi ke user
            Mail::to($loan->user->email)->send(new PeminjamanKonfirmasi($loan));

            session()->flash('success', 'Peminjaman berhasil dikonfirmasi dan notifikasi email telah dikirim ke peminjam.');

            // Reset form
            $this->selectedLoanId = null;
        } catch (\Exception $e) {
            session()->flash('error', 'Terjadi kesalahan: ' . $e->getMessage());
        } finally {
            $this->loadingKonfirmasiPeminjaman = false;
        }
    }

    // Fungsi untuk mengonfirmasi pengembalian
    public function setSelectedLoanForReturn($selectedLoanId)
    {
        $this->selectedLoanId = $selectedLoanId;
        // $this->bukti_kembali = null;
    }

    public function konfirmasiPengembalian($loanId)
    {
        $this->loadingKonfirmasiPengembalian = true;
        
        try {
            $loan = LoanHistory::with(['user', 'book'])->find($loanId);

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

            // Update status peminjaman menjadi selesai
            $loan->update([
                'status' => 'selesai',
                'tanggal_kembali' => now(),
            ]);

            // Kembalikan stok buku
            $book->increment('stok');

            // Kirim email notifikasi ke user
            Mail::to($loan->user->email)->send(new PengembalianKonfirmasi($loan));

            session()->flash('success', 'Pengembalian berhasil dikonfirmasi dan notifikasi email telah dikirim ke peminjam.');

            // Reset form
            $this->selectedLoanId = null;
        } catch (\Exception $e) {
            session()->flash('error', 'Terjadi kesalahan: ' . $e->getMessage());
        } finally {
            $this->loadingKonfirmasiPengembalian = false;
        }
    }

    public function setSelectedLoanForProof($loanId)
    {
        $this->selectedLoanId = $loanId;
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
            'pending' => '<span class="badge bg-primary">Baru</span>',
            'dipinjam' => '<span class="badge bg-primary">Dipinjam</span>',
            'dikembalikan' => '<span class="badge bg-warning">Dikembalikan</span>',
            'terlambat' => '<span class="badge bg-danger">Terlambat</span>',
            'selesai' => '<span class="badge bg-success">Selesai</span>',
            'ditolak' => '<span class="badge bg-dark">Ditolak</span>',
            'dibatalkan' => '<span class="badge bg-secondary">Dibatalkan</span>',
            default => '<span class="badge bg-secondary">Unknown</span>',
        };
    }

    public function getFineDetails($loan)
    {
        if ($loan->denda > 0) {
            $fineInfo = $loan->getFineInfo();
            return [
                'amount' => $loan->denda,
                'days_overdue' => $fineInfo['days_overdue'],
                'fine_per_day' => $fineInfo['fine_per_day'],
                'due_date' => $fineInfo['due_date'],
                'is_paid' => $loan->denda_dibayar,
            ];
        }
        return null;
    }

    public function markFineAsPaid($loanId)
    {
        // $this->validate([
        //     'bukti_kembali' => 'required|image|max:2048',
        // ]);

        try {
            $loan = LoanHistory::find($loanId);
            if ($loan && $loan->denda > 0) {
                // Upload bukti pengembalian
                // $buktiPath = $this->bukti_kembali->store('bukti-kembali', 'public');

                $loan->update([
                    'denda_dibayar' => true,
                    'tanggal_kembali' => now(),
                    // 'bukti_kembali' => $buktiPath,
                    'status' => 'selesai',
                ]);

                // Kembalikan stok buku
                $book = Book::find($loan->id_buku);
                if ($book) {
                    $book->increment('stok');
                }

                session()->flash('success', 'Denda berhasil ditandai sebagai sudah dibayar dan pengembalian dikonfirmasi.');

                // Reset form
                $this->selectedLoanId = null;
                // $this->bukti_kembali = null;
            }
        } catch (\Exception $e) {
            session()->flash('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function setSelectedLoanForFine($loanId)
    {
        $this->selectedLoanId = $loanId;
        // $this->bukti_kembali = null;
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }
    public function updatingFilterStatus()
    {
        $this->resetPage();
    }
    public function updatingFilterTanggalAwal()
    {
        $this->resetPage();
    }
    public function updatingFilterTanggalAkhir()
    {
        $this->resetPage();
    }

    public function render()
    {
        $query = LoanHistory::with(['user', 'book']);
        
        if ($this->search) {
            $query->where(function ($q) {
                $q->whereHas('user', function ($subQ) {
                    $subQ->where('name', 'like', '%' . $this->search . '%');
                })->orWhereHas('book', function ($subQ) {
                    $subQ->where('judul', 'like', '%' . $this->search . '%');
                });
            });
        }
        
        if ($this->filterStatus) {
            $query->where('status', $this->filterStatus);
        }
        
        if ($this->filterTanggalAwal) {
            $query->whereDate('tanggal_pinjam', '>=', $this->filterTanggalAwal);
        }
        
        if ($this->filterTanggalAkhir) {
            $query->whereDate('tanggal_pinjam', '<=', $this->filterTanggalAkhir);
        }
        
        $loans = $query->orderBy('created_at', 'desc')->paginate(10);
        
        return view('livewire.admin.peminjaman', [
            'loans' => $loans,
        ]);
    }
}