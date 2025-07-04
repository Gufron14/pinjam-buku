<?php

namespace App\Livewire\Admin;

use App\Models\Book;
use App\Models\User;
use App\Models\LoanHistory;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Title;
use Livewire\Attributes\Layout;
use Carbon\Carbon;

#[Title('Laporan')]
#[Layout('layouts.master')]

class Laporan extends Component
{
    use WithPagination;

    public $activeTab = 'peminjaman';
    public $search = '';
    public $statusFilter = '';
    public $startDate = '';
    public $endDate = '';
    public $perPage = 10;
    
    // Print filters
    public $printStartDate = '';
    public $printEndDate = '';
    public $printStatus = '';
    public $printType = 'peminjaman';

    protected $paginationTheme = 'bootstrap';

    public function mount()
    {
        // Set default date range to current month
        $this->startDate = Carbon::now()->startOfMonth()->format('Y-m-d');
        $this->endDate = Carbon::now()->endOfMonth()->format('Y-m-d');
        
        $this->printStartDate = $this->startDate;
        $this->printEndDate = $this->endDate;
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingStatusFilter()
    {
        $this->resetPage();
    }

    public function updatingStartDate()
    {
        $this->resetPage();
    }

    public function updatingEndDate()
    {
        $this->resetPage();
    }

    public function setActiveTab($tab)
    {
        $this->activeTab = $tab;
        $this->resetPage();
    }

    public function resetFilters()
    {
        $this->search = '';
        $this->statusFilter = '';
        $this->startDate = Carbon::now()->startOfMonth()->format('Y-m-d');
        $this->endDate = Carbon::now()->endOfMonth()->format('Y-m-d');
        $this->resetPage();
    }

    // Check and update overdue loans
    public function checkOverdueLoans()
    {
        $overdueLoans = LoanHistory::where('status', 'dipinjam')->get();
        
        foreach ($overdueLoans as $loan) {
            $loan->checkAndUpdateOverdueStatus();
        }
    }

    // Bayar denda
    public function bayarDenda($loanId)
    {
        $loan = LoanHistory::find($loanId);
        
        if ($loan && $loan->denda > 0 && !$loan->denda_dibayar) {
            $loan->denda_dibayar = true;
            $loan->save();
            
            session()->flash('message', 'Denda berhasil dibayar!');
            $this->dispatch('showAlert', [
                'type' => 'success',
                'message' => 'Denda sebesar Rp ' . number_format($loan->denda, 0, ',', '.') . ' berhasil dibayar!'
            ]);
        } else {
            $this->dispatch('showAlert', [
                'type' => 'error',
                'message' => 'Gagal memproses pembayaran denda!'
            ]);
        }
    }

    public function getLaporanPeminjamanProperty()
    {
        $query = LoanHistory::with(['user', 'book'])
            ->when($this->search, function($q) {
                $q->whereHas('user', function($query) {
                    $query->where('name', 'like', '%' . $this->search . '%')
                          ->orWhere('email', 'like', '%' . $this->search . '%');
                })->orWhereHas('book', function($query) {
                    $query->where('judul', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->statusFilter, function($q) {
                $q->where('status', $this->statusFilter);
            })
            ->when($this->startDate, function($q) {
                $q->whereDate('tanggal_pinjam', '>=', $this->startDate);
            })
            ->when($this->endDate, function($q) {
                $q->whereDate('tanggal_pinjam', '<=', $this->endDate);
            })
            ->orderBy('tanggal_pinjam', 'desc');

        return $query->paginate($this->perPage);
    }

    public function getLaporanPengembalianProperty()
    {
        $query = LoanHistory::with(['user', 'book'])
            ->where('status', 'dikembalikan')
            ->when($this->search, function($q) {
                $q->whereHas('user', function($query) {
                    $query->where('name', 'like', '%' . $this->search . '%')
                          ->orWhere('email', 'like', '%' . $this->search . '%');
                })->orWhereHas('book', function($query) {
                    $query->where('judul', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->startDate, function($q) {
                $q->whereDate('tanggal_kembali', '>=', $this->startDate);
            })
            ->when($this->endDate, function($q) {
                $q->whereDate('tanggal_kembali', '<=', $this->endDate);
            })
            ->orderBy('tanggal_kembali', 'desc');

        return $query->paginate($this->perPage);
    }

    public function getLaporanDendaProperty()
    {
        $query = LoanHistory::with(['user', 'book'])
            ->where(function($q) {
                $q->where('status', 'terlambat')
                  ->orWhere('denda', '>', 0);
            })
            ->when($this->search, function($q) {
                $q->whereHas('user', function($query) {
                    $query->where('name', 'like', '%' . $this->search . '%')
                          ->orWhere('email', 'like', '%' . $this->search . '%');
                })->orWhereHas('book', function($query) {
                    $query->where('judul', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->startDate, function($q) {
                $q->whereDate('tanggal_pinjam', '>=', $this->startDate);
            })
            ->when($this->endDate, function($q) {
                $q->whereDate('tanggal_pinjam', '<=', $this->endDate);
            })
            ->orderBy('denda', 'desc');

        return $query->paginate($this->perPage);
    }

    public function getStatistikProperty()
    {
        $startDate = $this->startDate ?: Carbon::now()->startOfMonth();
        $endDate = $this->endDate ?: Carbon::now()->endOfMonth();

        return [
            'total_peminjaman' => LoanHistory::whereBetween('tanggal_pinjam', [$startDate, $endDate])->count(),
            'total_pengembalian' => LoanHistory::where('status', 'dikembalikan')
                ->whereBetween('tanggal_kembali', [$startDate, $endDate])->count(),
            'total_terlambat' => LoanHistory::where('status', 'terlambat')
                ->whereBetween('tanggal_pinjam', [$startDate, $endDate])->count(),
            'total_denda' => LoanHistory::whereBetween('tanggal_pinjam', [$startDate, $endDate])
                ->sum('denda'),
            'denda_terbayar' => LoanHistory::where('denda_dibayar', true)
                ->whereBetween('tanggal_pinjam', [$startDate, $endDate])
                ->sum('denda'),
            'buku_terpopuler' => $this->getBukuTerpopuler($startDate, $endDate)
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

    public function cetakLaporan()
    {
        $this->validate([
            'printStartDate' => 'required|date',
            'printEndDate' => 'required|date|after_or_equal:printStartDate',
            'printType' => 'required|in:peminjaman,pengembalian,denda'
        ]);

        // Redirect to print page with parameters
        return redirect()->route('laporan.cetak', [
            'type' => $this->printType,
            'start_date' => $this->printStartDate,
            'end_date' => $this->printEndDate,
            'status' => $this->printStatus
        ]);
    }

    public function render()
    {
        // Auto check overdue loans
        $this->checkOverdueLoans();
        
        return view('livewire.admin.laporan', [
            'laporanPeminjaman' => $this->laporanPeminjaman,
            'laporanPengembalian' => $this->laporanPengembalian,
            'laporanDenda' => $this->laporanDenda,
            'statistik' => $this->statistik
        ]);
    }
}
