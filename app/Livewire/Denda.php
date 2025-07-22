<?php

namespace App\Livewire;

use Carbon\Carbon;
use Livewire\Component;
use App\Models\LoanHistory;
use Livewire\Attributes\Title;
use Illuminate\Support\Facades\Auth;

#[Title('Anda Didenda')]

class Denda extends Component
{
    public $dendaData = [];
    public $totalDenda = 0;

    public function mount()
    {
        // Cek apakah user memiliki denda yang belum dibayar
        $hasUnpaidFine = LoanHistory::where('id_user', Auth::id())->where('status', 'terlambat')->where('denda_dibayar', false)->where('denda', '>', 0)->exists();

        // Jika tidak ada denda, redirect ke home
        if (!$hasUnpaidFine) {
            return redirect()->route('/');
        }

        $this->loadDendaData();
    }

    public function loadDendaData()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        // Ambil semua pinjaman yang terlambat dan belum bayar denda
        $loans = LoanHistory::with(['book', 'user'])
            ->where('id_user', Auth::id())
            ->where('status', 'terlambat')
            ->where('denda_dibayar', false)
            ->where('denda', '>', 0)
            ->get();

        $this->dendaData = $loans
            ->map(function ($loan) {
                $dueDate = Carbon::parse($loan->tanggal_kembali)->setTimezone('Asia/Jakarta');
                $now = Carbon::now('Asia/Jakarta');
                $overdueDays = $dueDate->diffInDays($now);
                $overdueHours = $dueDate->diffInHours($now) % 24;
                $overdueMinutes = $dueDate->diffInMinutes($now) % 60;

                return [
                    'id_pinjaman' => $loan->id_pinjaman,
                    'judul_buku' => $loan->book->judul ?? 'Buku tidak ditemukan',
                    'tanggal_pinjam' => Carbon::parse($loan->tanggal_pinjam)->setTimezone('Asia/Jakarta')->format('d/m/Y'),
                    'tanggal_kembali' => $dueDate->format('d/m/Y H:i'),
                    'overdue_days' => $overdueDays,
                    'overdue_hours' => $overdueHours,
                    'overdue_minutes' => $overdueMinutes,
                    'denda' => $loan->denda,
                    'status' => $loan->status,
                ];
            })
            ->toArray();

        $this->totalDenda = $loans->sum('denda');
    }

    public function bayarDenda($idPinjaman)
    {
        try {
            $loan = LoanHistory::find($idPinjaman);

            if ($loan && $loan->id_user == Auth::id()) {
                $loan->update([
                    'denda_dibayar' => true,
                    'tanggal_bayar_denda' => Carbon::now('Asia/Jakarta'),
                ]);

                $this->loadDendaData();

                session()->flash('success', 'Denda berhasil dibayar!');

                // Jika tidak ada denda lagi, redirect ke home
                if (empty($this->dendaData)) {
                    return redirect()->route('/');
                }
            }
        } catch (\Exception $e) {
            session()->flash('error', 'Terjadi kesalahan saat membayar denda.');
        }
    }

    public function render()
    {
        return view('livewire.denda');
    }
}
