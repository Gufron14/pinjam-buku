<?php

namespace App\Livewire\Buku;

use App\Models\LoanHistory;
use Livewire\Component;
use Livewire\Attributes\Title;
use App\Models\Book;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Mail;
use App\Mail\PermohonanPeminjaman;
use App\Mail\PermohonanPengembalian;

#[Title('Daftar Buku')]
class DaftarBuku extends Component
{
    use WithPagination;

    public $search = '';
    public $kategoriFilter = '';
    public $genreFilter = '';
    public $jenisFilter = '';
    public $bookId;
    public $umurFilter = '';
    
    // Loading states
    public $loadingPinjamBuku = [];
    public $loadingKembalikanBuku = [];

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
        // Reset filter jenis ketika kategori berubah
        $this->jenisFilter = '';
    }

    public function updatingGenreFilter()
    {
        $this->resetPage();
    }

    public function updatingJenisFilter()
    {
        $this->resetPage();
    }

    public function updatingUmurFilter()
    {
        $this->resetPage();
    }

    public function resetFilters()
    {
        $this->search = '';
        $this->kategoriFilter = '';
        $this->genreFilter = '';
        $this->jenisFilter = '';
        $this->umurFilter = '';
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

        $this->loadingPinjamBuku[$bookId] = true;

        try {
            $user = auth()->user();

            // Cek verifikasi email
            if (is_null($user->email_verified_at)) {
                $this->dispatch('showAlertPinjam', [
                    'type' => 'error',
                    'message' => 'Akun kamu belum terverifikasi email. Silakan verifikasi email terlebih dahulu sebelum meminjam buku.',
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

            // Buat catatan peminjaman dengan status pending
            $loan = LoanHistory::create([
                'id_user' => $user->id,
                'id_buku' => $bookId,
                'status' => 'pending',
                'tanggal_pinjam' => now(),
                'tanggal_kembali' => null,
            ]);

            // Load relasi untuk email
            $loan->load(['user', 'book']);

            // Kirim email notifikasi ke admin
            $adminEmail = env('ADMIN_EMAIL', 'younggufron.mmnm@gmail.com');
            Mail::to($adminEmail)->send(new PermohonanPeminjaman($loan));

            // Kirim event sukses
            $this->dispatch('showAlertPinjam', [
                'type' => 'success',
                'message' => 'Permohonan peminjaman berhasil dikirim dan notifikasi telah dikirim ke admin!',
            ]);
        } catch (\Exception $e) {
            $this->dispatch('showAlertPinjam', [
                'type' => 'error',
                'message' => 'Terjadi kesalahan: ' . $e->getMessage(),
            ]);
        } finally {
            $this->loadingPinjamBuku[$bookId] = false;
        }
    }

    public function kembalikanBuku($bookId)
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $this->loadingKembalikanBuku[$bookId] = true;

        try {
            $user = auth()->user();

            // Cari transaksi yang sedang dipinjam
            $transaction = LoanHistory::with(['user', 'book'])->where('id_user', $user->id)->where('id_buku', $bookId)->where('status', 'dipinjam')->first();

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

            // Kirim email notifikasi ke admin
            $adminEmail = env('ADMIN_EMAIL', 'younggufron.mmnm@gmail.com');
            Mail::to($adminEmail)->send(new PermohonanPengembalian($transaction));

            // Kirim event sukses
            $this->dispatch('showAlertKembali', [
                'type' => 'success',
                'message' => 'Permohonan pengembalian berhasil dikirim dan notifikasi telah dikirim ke admin!',
            ]);
        } catch (\Exception $e) {
            $this->dispatch('showAlertKembali', [
                'type' => 'error',
                'message' => 'Terjadi kesalahan: ' . $e->getMessage(),
            ]);
        } finally {
            $this->loadingKembalikanBuku[$bookId] = false;
        }
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
        $user = auth()->user();
        $umur = $user ? $user->umur : null;

        // Show age filter for users 17+ OR when no user is logged in
        $showUmurFilter = !$user || ($umur && $umur >= 17);

        $booksQuery = Book::with(['category', 'genre', 'type'])
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
            });

        // Age-based filtering logic
        if ($user && $umur !== null) {
            if ($umur <= 16) {
                // Rule 1: User 16 ke bawah tidak bisa melihat buku untuk_umur 17+
                $booksQuery = $booksQuery->where('untuk_umur', '<', 17);
            } else {
                // Rule 2: User 17+ bisa melihat semua buku, tapi bisa filter dengan umur filter
                if ($this->umurFilter === '17+') {
                    $booksQuery = $booksQuery->where('untuk_umur', '>=', 17);
                } elseif ($this->umurFilter === 'under_17') {
                    $booksQuery = $booksQuery->where('untuk_umur', '<', 17);
                }
                // Jika umurFilter kosong, tampilkan semua buku
            }
        } else {
            // Jika user tidak login, tampilkan semua buku dan bisa filter berdasarkan umur
            if ($this->umurFilter === '17+') {
                $booksQuery = $booksQuery->where('untuk_umur', '>=', 17);
            } elseif ($this->umurFilter === 'under_17') {
                $booksQuery = $booksQuery->where('untuk_umur', '<', 17);
            }
            // Jika umurFilter kosong, tampilkan semua buku
        }

        $books = $booksQuery->orderBy('created_at', 'desc')->paginate(12);
        $categories = \App\Models\Categories::all();
        $genres = \App\Models\Genre::all();
        
        // Filter types berdasarkan kategori yang dipilih
        if ($this->kategoriFilter) {
            $types = \App\Models\Type::where('id_kategori', $this->kategoriFilter)->get();
        } else {
            $types = \App\Models\Type::all();
        }

        return view('livewire.buku.daftar-buku', [
            'books' => $books,
            'categories' => $categories,
            'genres' => $genres,
            'types' => $types,
            'showUmurFilter' => $showUmurFilter,
            'umurFilter' => $this->umurFilter,
        ]);
    }
}