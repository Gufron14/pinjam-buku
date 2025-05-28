<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\Attributes\Title;
use Livewire\Attributes\Layout;
use App\Models\LoanHistory;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

#[Title('Peminjaman Buku')]
#[Layout('layouts.master')]
class Peminjaman extends Component
{
    use WithPagination, WithFileUploads;
    public $bukti_pinjam;
    public $bukti_kembali;

    public $selectedLoanId;
    public $buktiPembayaran;

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

    public function setujuiPeminjaman($loanId)
    {
        $this->validate(
            [
                'bukti_pinjam' => 'required|image|max:2048',
            ],
            [
                'bukti_pinjam.required' => 'Bukti pinjam harus dilampirkan',
                'bukti_pinjam.image' => 'File harus berupa gambar',
                'bukti_pinjam.max' => 'Ukuran file maksimal 2MB',
            ],
        );

        try {
            $loan = LoanHistory::find($loanId);

            if (!$loan) {
                session()->flash('message', 'Data peminjaman tidak ditemukan.');
                return;
            }

            if ($loan->status !== 'pending' || $loan->konfirmasi_admin) {
                session()->flash('message', 'Status peminjaman tidak valid untuk disetujui.');
                return;
            }

            $filename = $this->bukti_pinjam->store('bukti-pinjam', 'public');

            $loan->update([
                'konfirmasi_admin' => true,
                'status' => 'dipinjam',
                'bukti_pinjam' => $filename,
            ]);

            session()->flash('message', 'Peminjaman berhasil disetujui.');
            $this->reset('bukti_pinjam');
        } catch (\Exception $e) {
            session()->flash('message', 'Terjadi kesalahan saat menyetujui peminjaman: ' . $e->getMessage());
        }
    }

    public function konfirmasiPengembalian($loanId)
    {
        $this->validate(
            [
                'bukti_kembali' => 'required|image|max:2048',
            ],
            [
                'bukti_kembali.required' => 'Bukti pinjam harus dilampirkan',
                'bukti_kembali.image' => 'File harus berupa gambar',
                'bukti_kembali.max' => 'Ukuran file maksimal 2MB',
            ],
        );

        try {
            $loan = LoanHistory::find($loanId);

            if (!$loan) {
                session()->flash('message', 'Data peminjaman tidak ditemukan.');
                return;
            }

            if ($loan->status !== 'dikembalikan' || $loan->konfirmasi_admin) {
                session()->flash('message', 'Status pengembalian tidak valid untuk disetujui.');
                return;
            }

            $filename = $this->bukti_kembali->store('bukti-kembali', 'public');

            $loan->update([
                'status' => 'selesai',
                'konfirmasi_admin' => true,
                'tanggal_kembali' => now()->format('Y-m-d'),
                'bukti_kembali' => $filename,
            ]);

            session()->flash('message', 'Peminjaman berhasil disetujui.');
            $this->reset('bukti_kembali');
        } catch (\Exception $e) {
            session()->flash('message', 'Terjadi kesalahan saat menyetujui peminjaman: ' . $e->getMessage());
        }
    }

    public function tolakPeminjaman($loanId)
    {
        $loan = LoanHistory::find($loanId);
        if ($loan && !$loan->konfirmasi_admin) {
            $loan->delete();

            session()->flash('message', 'Peminjaman berhasil ditolak dan dihapus.');
        }
    }

    public function lihatBuktiPembayaran($loanId)
    {
        $this->selectedLoanId = $loanId;
        $loan = LoanHistory::find($loanId);
        $this->buktiPembayaran = $loan ? $loan->bukti_pembayaran : null;
    }

    public function konfirmasiPembayaran()
    {
        if ($this->selectedLoanId) {
            $loan = LoanHistory::find($this->selectedLoanId);
            if ($loan) {
                $loan->konfirmasi_admin = true;
                $loan->status = 'dikembalikan';
                $loan->tanggal_kembali = now()->format('Y-m-d');
                $loan->save();

                session()->flash('message', 'Pembayaran denda berhasil dikonfirmasi.');
                $this->reset(['selectedLoanId', 'buktiPembayaran']);
            }
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
