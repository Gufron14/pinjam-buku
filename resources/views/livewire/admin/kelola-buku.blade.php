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
                                    <button class="btn btn-warning btn-sm" wire:click="openEditModal({{ $book->id_buku }})" title="Edit Buku">
                                        <i class="fas fa-edit"></i>                                    </button>
                                    <button class="btn btn-danger btn-sm" wire:click="delete({{ $book->id_buku }})"
                                        wire:confirm="Apakah Anda yakin ingin menghapus buku ini?" title="Hapus Buku">
                                        <i class="fas fa-trash"></i>                                    </button>
                                    <button class="btn btn-primary btn-sm" wire:click="openViewModal({{ $book->id_buku }})" title="Lihat Detail Buku">
                                        <i class="fas fa-eye"></i>                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10" class="text-center">Tidak ada data buku</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

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
                                            <select class="form-select" id="id_kategori" wire:model="id_kategori"
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
                                            <label for="id_genre" class="form-label">Genre</label>
                                            <select class="form-select" id="id_genre" wire:model="id_genre"
                                                {{ $isReadOnly ? 'disabled' : '' }}>
                                                <option value="">Pilih Genre</option>
                                                @foreach ($genres as $genre)
                                                    <option value="{{ $genre->id_genre }}">{{ $genre->nama_genre }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('id_genre')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label for="id_jenis" class="form-label">Jenis</label>
                                            <select class="form-select" id="id_jenis" wire:model="id_jenis"
                                                {{ $isReadOnly ? 'disabled' : '' }}>
                                                <option value="">Pilih Jenis</option>
                                                @foreach ($types as $type)
                                                    <option value="{{ $type->id_jenis }}">{{ $type->nama_jenis }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('id_jenis')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="stok" class="form-label">Stok</label>
                                            <input type="number" class="form-control" id="stok"
                                                wire:model="stok" {{ $isReadOnly ? 'readonly' : '' }}>
                                            @error('stok')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="tahun_terbit" class="form-label">Tahun Terbit</label>
                                            <input type="number" class="form-control" id="tahun_terbit"
                                                wire:model="tahun_terbit" {{ $isReadOnly ? 'readonly' : '' }}>
                                            @error('tahun_terbit')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
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
