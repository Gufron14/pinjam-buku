<?php

namespace App\Livewire\Admin;

use App\Models\Book;
use App\Models\Category;
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
    public $filteredTypes = [];
    public $filteredGenres = [];

    // Form properties
    public $bookId;
    public $judul;
    public $id_kategori;
    public $id_genre;
    public $id_jenis;
    public $stok;
    public $penulis;
    public $tahun_terbit;
    public $untuk_umur;
    public $nama_kategori;
    public $nama_jenis;
    public $nama_genre;

    // Modal state
    public $modalMode = 'create'; // create, edit, view
    public $modalTitle = 'Tambah Buku';
    public $isReadOnly = false;

    public function mount()
    {
        // Initial load will be handled by render method
        $this->categories = Category::all();
        $this->genres = Genre::all();
        $this->types = Type::all();
        $this->filteredTypes = $this->types;
        $this->filteredGenres = $this->genres;
    }

    // Search, filter, and pagination properties
    public $search = '';
    public $filterKategori = '';
    public $filterJenis = '';
    public $perPage = 10;
    public $page = 1;
    public $totalBooks = 0;

    public function loadData()
    {
        $query = Book::with(['category', 'genre', 'type']);
        
        // Apply filters
        if ($this->search) {
            $query->where('judul', 'like', '%' . $this->search . '%');
        }
        if ($this->filterKategori) {
            $query->where('id_kategori', $this->filterKategori);
        }
        if ($this->filterJenis) {
            $query->where('id_jenis', $this->filterJenis);
        }
        
        // Sort by newest first (latest created_at)
        $query->orderBy('created_at', 'desc');
        
        // Get total count before pagination
        $this->totalBooks = $query->count();
        
        // Apply pagination
        $this->books = $query
            ->skip(($this->page - 1) * $this->perPage)
            ->take($this->perPage)
            ->get();
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
        $this->untuk_umur = $book->untuk_umur;
        
        // Update filtered options based on selected values
        $this->updateFilteredTypes();
        $this->updateFilteredGenres();
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
        $this->untuk_umur = '';
        
        // Reset filtered options
        $this->filteredTypes = $this->types;
        $this->filteredGenres = $this->genres;
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
            'untuk_umur' => 'nullable|string|max:255',
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
                'untuk_umur' => $this->untuk_umur,
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
                'untuk_umur' => $this->untuk_umur,
            ]);
            session()->flash('message', 'Buku berhasil diperbarui!');
        }

        $this->dispatch('close-modal');
    }

    // Methods for handling hierarchical filtering
    public function updatedIdKategori()
    {
        $this->id_jenis = '';
        $this->id_genre = '';
        $this->updateFilteredTypes();
        $this->updateFilteredGenres();
    }

    public function updatedIdJenis()
    {
        $this->id_genre = '';
        $this->updateFilteredGenres();
    }

    public function updateFilteredTypes()
    {
        if ($this->id_kategori) {
            $this->filteredTypes = Type::where('id_kategori', $this->id_kategori)->get();
        } else {
            $this->filteredTypes = $this->types;
        }
    }

    public function updateFilteredGenres()
    {
        if ($this->id_jenis) {
            $this->filteredGenres = Genre::where('id_jenis', $this->id_jenis)->get();
        } else if ($this->id_kategori) {
            // Get genres through types that belong to selected category
            $typeIds = Type::where('id_kategori', $this->id_kategori)->pluck('id_jenis');
            $this->filteredGenres = Genre::whereIn('id_jenis', $typeIds)->get();
        } else {
            $this->filteredGenres = $this->genres;
        }
    }

    // Filter methods for table filtering
    public function getFilteredTypesForFilter()
    {
        if ($this->filterKategori) {
            return Type::where('id_kategori', $this->filterKategori)->get();
        }
        return $this->types;
    }

    // Tambahkan methods untuk save kategori, jenis, genre
    public function saveKategori()
    {
        $this->validate([
            'nama_kategori' => 'required|string|max:255|unique:categories,nama_kategori',
        ]);

        Category::create([
            'nama_kategori' => $this->nama_kategori,
        ]);

        $this->nama_kategori = '';
        $this->categories = Category::all(); // Refresh categories only
        session()->flash('message', 'Kategori berhasil ditambahkan!');
        $this->dispatch('close-modal');
    }

    public function saveJenis()
    {
        $this->validate([
            'nama_jenis' => 'required|string|max:255',
            'id_kategori' => 'required|exists:categories,id_kategori',
        ]);

        Type::create([
            'nama_jenis' => $this->nama_jenis,
            'id_kategori' => $this->id_kategori,
        ]);

        $this->nama_jenis = '';
        $this->id_kategori = '';
        $this->types = Type::all(); // Refresh types only
        session()->flash('message', 'Jenis berhasil ditambahkan!');
        $this->dispatch('close-modal');
    }

    public function saveGenre()
    {
        $this->validate([
            'nama_genre' => 'required|string|max:255',
            'id_jenis' => 'required|exists:types,id_jenis',
        ]);

        Genre::create([
            'nama_genre' => $this->nama_genre,
            'id_jenis' => $this->id_jenis,
        ]);

        $this->nama_genre = '';
        $this->id_jenis = '';
        $this->genres = Genre::all(); // Refresh genres only
        session()->flash('message', 'Genre berhasil ditambahkan!');
        $this->dispatch('close-modal');
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
        } else {
            session()->flash('error', 'Buku tidak ditemukan!');
        }
    }

    public function render()
    {
        $this->loadData();
        return view('livewire.admin.kelola-buku');
    }
    // Real-time update methods
    public function updatedSearch()
    {
        $this->resetPage();
        $this->loadData();
    }
    
    public function updatedFilterKategori()
    {
        $this->resetPage();
        $this->loadData();
    }
    
    public function updatedFilterJenis()
    {
        $this->resetPage();
        $this->loadData();
    }
    
    public function updatedPage()
    {
        $this->loadData();
    }
    
    public function updatedPerPage()
    {
        $this->resetPage();
        $this->loadData();
    }
    
    public function clearFilters()
    {
        $this->search = '';
        $this->filterKategori = '';
        $this->filterJenis = '';
        $this->resetPage();
        $this->loadData();
    }

    public function resetPage()
    {
        $this->page = 1;
    }

    public function nextPage()
    {
        if ($this->page < ceil($this->totalBooks / $this->perPage)) {
            $this->page++;
        }
    }
    
    public function prevPage()
    {
        if ($this->page > 1) {
            $this->page--;
        }
    }
}