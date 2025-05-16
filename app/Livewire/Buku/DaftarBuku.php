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

    // Add this method to the DaftarBuku class
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
        
        $user = auth()->user();

        // Cek apakah user sudah pinjam buku ini dan belum dikembalikan
        $alreadyBorrowed = LoanHistory::where('id_user', $user->id)->where('id_buku', $bookId)->whereNull('tanggal_kembali')->exists();

        if ($alreadyBorrowed) {
            session()->flash('error', 'Kamu sudah meminjam buku ini. Kembalikan dulu sebelum pinjam lagi.');
            return redirect()->back();
        }

        // Cek jumlah total pinjaman aktif user
        $activeBorrowCount = LoanHistory::where('id_user', $user->id)->whereNull('tanggal_kembali')->count();

        if ($activeBorrowCount >= 2) {
            session()->flash('error', 'Kamu hanya bisa meminjam maksimal 2 buku sekaligus.');
            return redirect()->back();
        }

        $book = Book::find($bookId);

        if (!$book) {
            session()->flash('error', 'Buku tidak ditemukan.');
            return redirect()->back();
        }

        if ($book->stok <= 0) {
            session()->flash('error', 'Stok buku tidak tersedia.');
            return redirect()->back();
        }

        // Kurangi stok buku
        $book->stok -= 1;
        $book->save();

        // Buat catatan peminjaman
        LoanHistory::create([
            'id_user' => $user->id,
            'id_buku' => $bookId,
            'status' => 'dipinjam',
            'tanggal_pinjam' => now(),
            'tanggal_kembali' => null,
        ]);

        // Kirim event sukses
        $this->dispatch('showAlertPinjam', [
            'type' => 'success',
            'message' => 'Buku berhasil dipinjam! Silakan ambil di Taman Baca Balarea!'
        ]);
    }

    public function kembalikanBuku($bookId)
    {
        $user = auth()->user();

        $transaction = LoanHistory::where('id_user', $user->id)->where('id_buku', $bookId)->whereNull('tanggal_kembali')->first();

        if (!$transaction) {
            session()->flash('error', 'Transaksi peminjaman tidak ditemukan.');
            return redirect()->back();
        }

        // Tandai pengembalian
        $transaction->update([
            'tanggal_kembali' => now(),
            'status' => 'dikembalikan',
        ]);

        // Tambahkan kembali stok buku
        $book = Book::find($bookId);
        $book->stok += 1;
        $book->save();

        // Kirim event sukses
        $this->dispatch('showAlertKembali', [
            'type' => 'success',
            'message' => 'Silakan kembalikan ke Taman Baca Balarea!'
        ]);
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
