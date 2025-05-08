<?php

namespace App\Livewire\Buku;

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
