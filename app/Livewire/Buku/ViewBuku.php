<?php

namespace App\Livewire\Buku;

use App\Models\Book;
use Livewire\Component;
use App\Models\LoanHistory;
use Livewire\Attributes\Title;

#[Title('Detail Buku')]
class ViewBuku extends Component
{
    public $buku;
    public $buku_id;

    public function mount($id)
    {
        $this->buku_id = $id;
        $this->buku = Book::with(['category', 'genre', 'type'])->findOrFail($id);
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

        session()->flash('message', 'Buku berhasil dipinjam!');
        return redirect()->back();
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

        session()->flash('message', 'Buku berhasil dikembalikan.');
        return redirect()->back();
    }

    public function render()
    {
        return view('livewire.buku.view-buku');
    }
}
