<div>
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h4 class="card-title">Kelola Buku</h4>
            <div>
                <button class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#tambahKategoriModal">
                    <i class="fas fa-plus me-2"></i>Kategori
                </button>
                <button class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#tambahJenisModal">
                    <i class="fas fa-plus me-2"></i>Jenis
                </button>
                <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#tambahGenreModal">
                    <i class="fas fa-plus me-2"></i>Genre
                </button>
                <button class="btn btn-primary btn-sm" wire:click="openCreateModal">
                    <i class="fas fa-plus me-2"></i>Buku
                </button>
            </div>
        </div>
        <div class="card-body">
            @if (session()->has('message'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('message') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <div class="row mb-3">
                <div class="col-md-4">
                    <div class="input-group">
                        <input type="text" class="form-control" placeholder="Cari judul buku..." wire:model.live.debounce.300ms="search">
                        @if($search)
                            <button class="btn btn-outline-secondary" type="button" wire:click="$set('search', '')" title="Hapus pencarian">
                                <i class="fas fa-times"></i>
                            </button>
                        @endif
                    </div>
                </div>
                <div class="col-md-3">
                    <select class="form-select" wire:model.live="filterKategori">
                        <option value="">Semua Kategori</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id_kategori }}">{{ $category->nama_kategori }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <select class="form-select" wire:model.live="filterJenis">
                        <option value="">Semua Jenis</option>
                        @foreach ($this->getFilteredTypesForFilter() as $type)
                            <option value="{{ $type->id_jenis }}">{{ $type->nama_jenis }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    @if($search || $filterKategori || $filterJenis)
                        <button class="btn btn-outline-secondary w-100" wire:click="clearFilters" title="Reset semua filter">
                            <i class="fas fa-undo me-1"></i>Reset Filter
                        </button>
                    @endif
                </div>
            </div>
            <table class="table table-bordered">
                <thead>
                    <tr class="text-center">
                        <th>No</th>
                        <th>Judul Buku</th>
                        {{-- <th>Kategori</th>
                        <th>Genre</th>
                        <th>Jenis</th>
                        <th>Penulis</th> --}}
                        <th>Stok Awal</th>
                        <th>Jumlah Peminjam</th>
                        <th>Stok Akhir</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($books as $index => $book)
                        <tr class="text-center">
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $book->judul }}</td>
                            {{-- <td>{{ $book->category->nama_kategori ?? '-' }}</td>
                            <td>{{ $book->genre->nama_genre ?? '-' }}</td>
                            <td>{{ $book->type->nama_jenis ?? '-' }}</td>
                            <td>{{ $book->penulis ?? '-' }}</td> --}}
                            <td>{{ $book->stok }}</td>
                            <td>{{ $this->getJumlahPeminjam($book->id_buku) }}</td>
                            <td>{{ $this->getStokAkhir($book->id_buku) }}</td>
                            <td>
                                <div class="btn-group">
                                    <button class="btn btn-warning btn-sm"
                                        wire:click="openEditModal({{ $book->id_buku }})" title="Edit Buku">
                                        <i class="fas fa-edit"></i> </button>
                                    <button class="btn btn-danger btn-sm" wire:click="delete({{ $book->id_buku }})"
                                        wire:confirm="Apakah Anda yakin ingin menghapus buku ini?" title="Hapus Buku">
                                        <i class="fas fa-trash"></i> </button>
                                    <button class="btn btn-primary btn-sm"
                                        wire:click="openViewModal({{ $book->id_buku }})" title="Lihat Detail Buku">
                                        <i class="fas fa-eye"></i> </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-4">
                                @if($search || $filterKategori || $filterJenis)
                                    <div class="text-muted">
                                        <i class="fas fa-search fa-2x mb-2"></i>
                                        <br>
                                        Tidak ada buku yang sesuai dengan kriteria pencarian
                                        <br>
                                        <small>Coba ubah kata kunci atau filter yang digunakan</small>
                                    </div>
                                @else
                                    <div class="text-muted">
                                        <i class="fas fa-book fa-2x mb-2"></i>
                                        <br>
                                        Belum ada data buku
                                        <br>
                                        <small>Klik tombol "Tambah Buku" untuk mulai menambahkan data</small>
                                    </div>
                                @endif
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            @if($totalBooks > 0)
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <small class="text-muted">
                        Menampilkan {{ ($page - 1) * $perPage + 1 }} - {{ min($page * $perPage, $totalBooks) }} dari {{ $totalBooks }} data
                    </small>
                    <div class="d-flex align-items-center">
                        <label class="me-2">Tampilkan:</label>
                        <select class="form-select form-select-sm" style="width: auto;" wire:model.live="perPage">
                            <option value="5">5</option>
                            <option value="10">10</option>
                            <option value="25">25</option>
                            <option value="50">50</option>
                        </select>
                        <span class="ms-2">per halaman</span>
                    </div>
                </div>
            @endif

            @if($totalBooks > $perPage)
            <nav aria-label="Page navigation">
                <ul class="pagination justify-content-center">
                    <li class="page-item {{ $page == 1 ? 'disabled' : '' }}">
                        <button class="page-link" wire:click="prevPage" {{ $page == 1 ? 'disabled' : '' }}>Previous</button>
                    </li>
                    @php
                        $totalPages = ceil($totalBooks / $perPage);
                        $startPage = max(1, $page - 2);
                        $endPage = min($totalPages, $page + 2);
                    @endphp
                    
                    @if($startPage > 1)
                        <li class="page-item">
                            <button class="page-link" wire:click="$set('page', 1)">1</button>
                        </li>
                        @if($startPage > 2)
                            <li class="page-item disabled">
                                <span class="page-link">...</span>
                            </li>
                        @endif
                    @endif
                    
                    @for ($i = $startPage; $i <= $endPage; $i++)
                        <li class="page-item {{ $page == $i ? 'active' : '' }}">
                            <button class="page-link" wire:click="$set('page', {{ $i }})">{{ $i }}</button>
                        </li>
                    @endfor
                    
                    @if($endPage < $totalPages)
                        @if($endPage < $totalPages - 1)
                            <li class="page-item disabled">
                                <span class="page-link">...</span>
                            </li>
                        @endif
                        <li class="page-item">
                            <button class="page-link" wire:click="$set('page', {{ $totalPages }})">{{ $totalPages }}</button>
                        </li>
                    @endif
                    
                    <li class="page-item {{ $page == $totalPages ? 'disabled' : '' }}">
                        <button class="page-link" wire:click="nextPage" {{ $page == $totalPages ? 'disabled' : '' }}>Next</button>
                    </li>
                </ul>
            </nav>
            @endif

            <!-- Modal Dinamis untuk Buku -->
            <div class="modal fade" id="bukuModal" tabindex="-1" aria-labelledby="bukuModalLabel" aria-hidden="true"
                wire:ignore.self>
                <div class="modal-dialog modal-lg modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h1 class="modal-title fs-5" id="bukuModalLabel">{{ $modalTitle }}</h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>
                        <form wire:submit.prevent="save">
                            <div class="modal-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="judul" class="form-label">Judul Buku</label>
                                            <input type="text" class="form-control" id="judul" wire:model="judul"
                                                {{ $isReadOnly ? 'readonly' : '' }}>
                                            @error('judul')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="penulis" class="form-label">Penulis</label>
                                            <input type="text" class="form-control" id="penulis"
                                                wire:model="penulis" {{ $isReadOnly ? 'readonly' : '' }}>
                                            @error('penulis')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label for="id_kategori" class="form-label">Kategori</label>
                                            <select class="form-select" id="id_kategori" wire:model.live="id_kategori"
                                                {{ $isReadOnly ? 'disabled' : '' }}>
                                                <option value="">Pilih Kategori</option>
                                                @foreach ($categories as $category)
                                                    <option value="{{ $category->id_kategori }}">
                                                        {{ $category->nama_kategori }}</option>
                                                @endforeach
                                            </select>
                                            @error('id_kategori')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label for="id_jenis" class="form-label">Jenis</label>
                                            <select class="form-select" id="id_jenis" wire:model.live="id_jenis"
                                                {{ $isReadOnly ? 'disabled' : '' }}>
                                                <option value="">Pilih Jenis</option>
                                                @foreach ($filteredTypes as $type)
                                                    <option value="{{ $type->id_jenis }}">{{ $type->nama_jenis }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('id_jenis')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label for="id_genre" class="form-label">Genre</label>
                                            <select class="form-select" id="id_genre" wire:model.live="id_genre"
                                                {{ $isReadOnly ? 'disabled' : '' }}>
                                                <option value="">Pilih Genre</option>
                                                @foreach ($filteredGenres as $genre)
                                                    <option value="{{ $genre->id_genre }}">{{ $genre->nama_genre }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('id_genre')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label for="stok" class="form-label">Stok</label>
                                            <input type="number" class="form-control" id="stok"
                                                wire:model="stok" {{ $isReadOnly ? 'readonly' : '' }}>
                                            @error('stok')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label for="tahun_terbit" class="form-label">Tahun Terbit</label>
                                            <input type="number" class="form-control" id="tahun_terbit"
                                                wire:model="tahun_terbit" {{ $isReadOnly ? 'readonly' : '' }}>
                                            @error('tahun_terbit')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label for="untuk_umur" class="form-label">Umur</label>
                                            <select class="form-select" id="untuk_umur" wire:model="untuk_umur"
                                                {{ $isReadOnly ? 'disabled' : '' }}>
                                                <option value="">Pilih Umur</option>
                                                <option value="16">17 Tahun Ke Bawah</option>
                                                <option value="17">17 Tahun Ke Atas</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary"
                                    data-bs-dismiss="modal">Tutup</button>
                                @if ($modalMode === 'create')
                                    <button type="submit" class="btn btn-primary">Simpan</button>
                                @elseif($modalMode === 'edit')
                                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                                @endif
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Modal untuk Kategori -->
            <div class="modal fade" id="tambahKategoriModal" tabindex="-1" aria-labelledby="tambahKategoriModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="tambahKategoriModalLabel">Tambah Kategori</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <form wire:submit.prevent="saveKategori">
                            <div class="modal-body">
                                <div class="mb-3">
                                    <label for="nama_kategori" class="form-label">Nama Kategori</label>
                                    <input type="text" class="form-control" id="nama_kategori" wire:model="nama_kategori" required>
                                    @error('nama_kategori')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                                                <div class="table-responsive">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Daftar Kategori</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse ($categories as $item)
                                                <tr>
                                                    <td>{{ $loop->iteration }}</td>
                                                    <td>{{ $item->nama_kategori }}</td>
                                            @empty
                                                    <td colspan="2">Tidak ada Jenis</td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                                <button type="submit" class="btn btn-success">Simpan</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Modal untuk Jenis -->
            <div class="modal fade" id="tambahJenisModal" tabindex="-1" aria-labelledby="tambahJenisModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="tambahJenisModalLabel">Tambah Jenis</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <form wire:submit.prevent="saveJenis">
                            <div class="modal-body">
                                <div class="mb-3">
                                    <label for="id_kategori_jenis" class="form-label">Kategori</label>
                                    <select class="form-select" id="id_kategori_jenis" wire:model="id_kategori" required>
                                        <option value="">Pilih Kategori</option>
                                        @foreach ($categories as $category)
                                            <option value="{{ $category->id_kategori }}">{{ $category->nama_kategori }}</option>
                                        @endforeach
                                    </select>
                                    @error('id_kategori')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="mb-3">
                                    <label for="nama_jenis" class="form-label">Nama Jenis</label>
                                    <input type="text" class="form-control" id="nama_jenis" wire:model="nama_jenis" required>
                                    @error('nama_jenis')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="table-responsive">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Daftar Jenis</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse ($types as $item)
                                                <tr>
                                                    <td>{{ $loop->iteration }}</td>
                                                    <td>{{ $item->nama_jenis }}</td>
                                            @empty
                                                    <td colspan="2">Tidak ada Jenis</td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                                <button type="submit" class="btn btn-info">Simpan</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Modal untuk Genre -->
            <div class="modal fade" id="tambahGenreModal" tabindex="-1" aria-labelledby="tambahGenreModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="tambahGenreModalLabel">Tambah Genre</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <form wire:submit.prevent="saveGenre">
                            <div class="modal-body">
                                <div class="mb-3">
                                    <label for="id_jenis_genre" class="form-label">Jenis</label>
                                    <select class="form-select" id="id_jenis_genre" wire:model="id_jenis" required>
                                        <option value="">Pilih Jenis</option>
                                        @foreach ($types as $type)
                                            <option value="{{ $type->id_jenis }}">{{ $type->nama_jenis }} ({{ $type->category->nama_kategori ?? 'Tanpa Kategori' }})</option>
                                        @endforeach
                                    </select>
                                    @error('id_jenis')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="mb-3">
                                    <label for="nama_genre" class="form-label">Nama Genre</label>
                                    <input type="text" class="form-control" id="nama_genre" wire:model="nama_genre" required>
                                    @error('nama_genre')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="table-responsive">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Daftar Genre</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse ($genres as $item)
                                                <tr>
                                                    <td>{{ $loop->iteration }}</td>
                                                    <td>{{ $item->nama_genre}}</td>
                                            @empty
                                                    <td colspan="2">Tidak ada Genre</td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                                <button type="submit" class="btn btn-warning">Simpan</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <!-- Script untuk mengontrol modal -->
    <script>
        document.addEventListener('livewire:init', () => {
            Livewire.on('open-modal', () => {
                const modal = new bootstrap.Modal(document.getElementById('bukuModal'));
                modal.show();
            });

            Livewire.on('close-modal', () => {
                const modal = bootstrap.Modal.getInstance(document.getElementById('bukuModal'));
                if (modal) {
                    modal.hide();
                }
            });
        });
    </script>
</div>