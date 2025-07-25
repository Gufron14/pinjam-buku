<?php

namespace App\Livewire\Admin;

use App\Models\Book;
use App\Models\Categories;
use App\Models\Genre;
use App\Models\Type;
use Livewire\Component;
use Livewire\Attributes\Title;
use Livewire\Attributes\Layout;

#[Title('Kelola Buku')]
#[Layout('layouts.master')]
class KelolaBuku extends Component
{
    public $books;
    public $categories;
    public $genres;
    public $types;

    // Form properties
    public $bookId;
    public $judul;
    public $id_kategori;
    public $id_genre;
    public $id_jenis;
    public $stok;
    public $penulis;
    public $tahun_terbit;
    public $nama_kategori;
    public $nama_jenis;
    public $nama_genre;

    // Modal state
    public $modalMode = 'create'; // create, edit, view
    public $modalTitle = 'Tambah Buku';
    public $isReadOnly = false;

    public function mount()
    {
        $this->loadData();
    }

    public function loadData()
    {
        $this->books = Book::with(['category', 'genre', 'type'])->get();
        $this->categories = Categories::all();
        $this->genres = Genre::all();
        $this->types = Type::all();
    }

    public function openCreateModal()
    {
        $this->resetForm();
        $this->modalMode = 'create';
        $this->modalTitle = 'Tambah Buku';
        $this->isReadOnly = false;
        $this->dispatch('open-modal');
    }

    public function openEditModal($id)
    {
        $book = Book::find($id);
        $this->fillForm($book);
        $this->modalMode = 'edit';
        $this->modalTitle = 'Edit Buku';
        $this->isReadOnly = false;
        $this->dispatch('open-modal');
    }

    public function openViewModal($id)
    {
        $book = Book::find($id);
        $this->fillForm($book);
        $this->modalMode = 'view';
        $this->modalTitle = 'Detail Buku';
        $this->isReadOnly = true;
        $this->dispatch('open-modal');
    }

    public function fillForm($book)
    {
        $this->bookId = $book->id_buku;
        $this->judul = $book->judul;
        $this->id_kategori = $book->id_kategori;
        $this->id_genre = $book->id_genre;
        $this->id_jenis = $book->id_jenis;
        $this->stok = $book->stok;
        $this->penulis = $book->penulis;
        $this->tahun_terbit = $book->tahun_terbit;
    }

    public function resetForm()
    {
        $this->bookId = null;
        $this->judul = '';
        $this->id_kategori = '';
        $this->id_genre = '';
        $this->id_jenis = '';
        $this->stok = '';
        $this->penulis = '';
        $this->tahun_terbit = '';
    }

    public function save()
    {
        $this->validate([
            'judul' => 'required|string|max:255',
            'id_kategori' => 'required|exists:categories,id_kategori',
            'id_genre' => 'required|exists:genres,id_genre',
            'id_jenis' => 'required|exists:types,id_jenis',
            'stok' => 'required|integer|min:0', 
            'penulis' => 'nullable|string|max:255',
            'tahun_terbit' => 'nullable|integer|min:1900|max:' . date('Y'),
        ]);

        if ($this->modalMode === 'create') {
            Book::create([
                'judul' => $this->judul,
                'id_kategori' => $this->id_kategori,
                'id_genre' => $this->id_genre,
                'id_jenis' => $this->id_jenis,
                'stok' => $this->stok,
                'penulis' => $this->penulis,
                'tahun_terbit' => $this->tahun_terbit,
            ]);
            session()->flash('message', 'Buku berhasil ditambahkan!');
        } else {
            $book = Book::find($this->bookId);
            $book->update([
                'judul' => $this->judul,
                'id_kategori' => $this->id_kategori,
                'id_genre' => $this->id_genre,
                'id_jenis' => $this->id_jenis,
                'stok' => $this->stok,
                'penulis' => $this->penulis,
                'tahun_terbit' => $this->tahun_terbit,
            ]);
            session()->flash('message', 'Buku berhasil diperbarui!');
        }

        $this->loadData();
        $this->dispatch('close-modal');
    }

    // Tambahkan methods untuk save kategori, jenis, genre
    public function saveKategori()
    {
        $this->validate([
            'nama_kategori' => 'required|string|max:255|unique:categories,nama_kategori',
        ]);

        Categories::create([
            'nama_kategori' => $this->nama_kategori,
        ]);

        $this->nama_kategori = '';
        $this->loadData();
        session()->flash('message', 'Kategori berhasil ditambahkan!');
        
    }

    public function saveJenis()
    {
        $this->validate([
            'nama_jenis' => 'required|string|max:255|unique:types,nama_jenis',
        ]);

        Type::create([
            'nama_jenis' => $this->nama_jenis,
        ]);

        $this->nama_jenis = '';
        $this->loadData();
        session()->flash('message', 'Jenis berhasil ditambahkan!');
    }

    public function saveGenre()
    {
        $this->validate([
            'nama_genre' => 'required|string|max:255|unique:genres,nama_genre',
        ]);

        Genre::create([
            'nama_genre' => $this->nama_genre,
        ]);

        $this->nama_genre = '';
        $this->loadData();
        session()->flash('message', 'Genre berhasil ditambahkan!');
    }

    public function getJumlahPeminjam($bookId)
    {
        // Asumsi ada relasi dengan tabel peminjaman
        return Book::find($bookId)->loanHistories()->where('status', 'dipinjam')->count();
    }

    public function getStokAkhir($bookId)
    {
        $book = Book::find($bookId);
        $jumlahPeminjam = $this->getJumlahPeminjam($bookId);
        return $book->stok - $jumlahPeminjam;
    }

    // Delete book
    public function delete($id)
    {
        $book = Book::find($id);
        if ($book) {
            $book->delete();
            session()->flash('message', 'Buku berhasil dihapus!');
            $this->loadData();
        } else {
            session()->flash('error', 'Buku tidak ditemukan!');
        }
    }

    public function render()
    {
        return view('livewire.admin.kelola-buku');
    }
}
