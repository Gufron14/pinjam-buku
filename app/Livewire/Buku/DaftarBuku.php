<?php

namespace App\Livewire\Buku;

use App\Models\LoanHistory;
use Livewire\Component;
use Livewire\Attributes\Title;
use App\Models\Book;
use Livewire\WithPagination;

#[Title('Daftar Buku')]
class DaftarBuku extends Component
{
    use WithPagination;

    public $search = '';
    public $kategoriFilter = '';
    public $genreFilter = '';
    public $jenisFilter = '';
    public $bookId;

    protected $queryString = [
        'search' => ['except' => ''],
        'kategoriFilter' => ['except' => ''],
        'genreFilter' => ['except' => ''],
        'jenisFilter' => ['except' => ''],
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingKategoriFilter()
    {
        $this->resetPage();
    }

    public function updatingGenreFilter()
    {
        $this->resetPage();
    }

    public function updatingJenisFilter()
    {
        $this->resetPage();
    }

    public function resetFilters()
    {
        $this->search = '';
        $this->kategoriFilter = '';
        $this->genreFilter = '';
        $this->jenisFilter = '';
        $this->resetPage();
    }

    public function mount($bookId = null)
    {
        $this->bookId = $bookId;
    }

    public function pinjamBuku($bookId)
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $user = auth()->user();

        // Check if user has unpaid fines
        if (LoanHistory::hasUnpaidFines($user->id)) {
            $this->dispatch('showAlertPinjam', [
                'type' => 'error',
                'message' => 'Anda memiliki denda yang belum dibayar. Silakan bayar denda untuk dapat meminjam buku lagi.',
            ]);
            return;
        }

        // Cek apakah user sudah pinjam buku ini dan belum selesai (pending, dipinjam, atau menunggu konfirmasi pengembalian)
        $alreadyBorrowed = LoanHistory::where('id_user', $user->id)
            ->where('id_buku', $bookId)
            ->whereIn('status', ['pending', 'dipinjam', 'menunggu_konfirmasi_pengembalian'])
            ->exists();

        if ($alreadyBorrowed) {
            $this->dispatch('showAlertPinjam', [
                'type' => 'error',
                'message' => 'Kamu sudah meminjam buku ini. Kembalikan dulu sebelum pinjam lagi.',
            ]);
            return;
        }

        // Cek jumlah total pinjaman aktif user (pending + dipinjam + menunggu konfirmasi pengembalian)
        $activeBorrowCount = LoanHistory::where('id_user', $user->id)
            ->whereIn('status', ['pending', 'dipinjam', 'dikembalikan'])
            ->count();

        if ($activeBorrowCount >= 2) {
            $this->dispatch('showAlertPinjam', [
                'type' => 'error',
                'message' => 'Kamu hanya bisa meminjam maksimal 2 buku sekaligus.',
            ]);
            return;
        }

        $book = Book::find($bookId);

        if (!$book) {
            $this->dispatch('showAlertPinjam', [
                'type' => 'error',
                'message' => 'Buku tidak ditemukan.',
            ]);
            return;
        }

        // Cek stok tersedia (langsung dari database)
        if ($book->stok <= 0) {
            $this->dispatch('showAlertPinjam', [
                'type' => 'error',
                'message' => 'Stok buku tidak tersedia.',
            ]);
            return;
        }

        // Cek stok tersedia (tidak termasuk yang sedang pending)
        // $pendingLoans = LoanHistory::where('id_buku', $bookId)
        //     ->whereIn('status', ['pending', 'dipinjam'])
        //     ->count();

        // $availableStock = $book->stok - $pendingLoans;

        // if ($availableStock <= 0) {
        //     $this->dispatch('showAlertPinjam', [
        //         'type' => 'error',
        //         'message' => 'Stok buku tidak tersedia.',
        //     ]);
        //     return;
        // }

        // Buat catatan peminjaman dengan status pending
        LoanHistory::create([
            'id_user' => $user->id,
            'id_buku' => $bookId,
            'status' => 'pending',
            'tanggal_pinjam' => now(),
            'tanggal_kembali' => null,
        ]);

        // Kirim event sukses
        $this->dispatch('showAlertPinjam', [
            'type' => 'success',
            'message' => 'Permohonan peminjaman berhasil dikirim! Menunggu konfirmasi admin.',
        ]);
    }

    public function kembalikanBuku($bookId)
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $user = auth()->user();

        // Cari transaksi yang sedang dipinjam
        $transaction = LoanHistory::where('id_user', $user->id)->where('id_buku', $bookId)->where('status', 'dipinjam')->first();

        if (!$transaction) {
            $this->dispatch('showAlertKembali', [
                'type' => 'error',
                'message' => 'Transaksi peminjaman tidak ditemukan atau buku belum dikonfirmasi dipinjam.',
            ]);
            return;
        }

        // Update status menjadi menunggu konfirmasi pengembalian
        $transaction->update([
            'status' => 'dikembalikan',
            'tanggal_kembali' => now(),
        ]);

        // Kirim event sukses
        $this->dispatch('showAlertKembali', [
            'type' => 'success',
            'message' => 'Permohonan pengembalian berhasil dikirim! Silakan antarkan buku ke Taman Baca Balarea dan tunggu konfirmasi admin.',
        ]);
    }

    /**
     * Method untuk mendapatkan status peminjaman user untuk buku tertentu
     */
    public function getStatusPeminjaman($userId, $bookId)
    {
        return LoanHistory::where('id_user', $userId)
            ->where('id_buku', $bookId)
            ->whereIn('status', ['pending', 'dipinjam', 'dikembalikan'])
            ->first();
    }

    public function render()
    {
        $books = Book::with(['category', 'genre', 'type'])
            ->when($this->search, function ($query) {
                return $query->where('judul', 'like', '%' . $this->search . '%')->orWhere('penulis', 'like', '%' . $this->search . '%');
            })
            ->when($this->kategoriFilter, function ($query) {
                return $query->where('id_kategori', $this->kategoriFilter);
            })
            ->when($this->genreFilter, function ($query) {
                return $query->where('id_genre', $this->genreFilter);
            })
            ->when($this->jenisFilter, function ($query) {
                return $query->where('id_jenis', $this->jenisFilter);
            })
            ->paginate(12);

        $categories = \App\Models\Categories::all();
        $genres = \App\Models\Genre::all();
        $types = \App\Models\Type::all();

        return view('livewire.buku.daftar-buku', [
            'books' => $books,
            'categories' => $categories,
            'genres' => $genres,
            'types' => $types,
        ]);
    }
}
