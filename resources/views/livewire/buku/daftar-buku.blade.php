<div class="container py-5">
    <!-- Header Section -->
    <div class="row mb-4">
        <div class="col-md-8">
            <h1 class="fw-bold text-success mb-1">Katalog Buku</h1>
            <p class="text-muted">Temukan koleksi buku terbaik untuk dibaca</p>
        </div>
        <div class="col-md-4">
            <div class="input-group shadow-sm rounded overflow-hidden">
                <span class="input-group-text bg-white border-0">
                    <i class="bi bi-search"></i>
                </span>
                <input wire:model.live.debounce.300ms="search" type="text" class="form-control border-0 py-2"
                    placeholder="Cari judul atau penulis...">
            </div>
        </div>
    </div>

    <!-- Filters Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <label class="form-label small text-muted">Kategori</label>
                            <select wire:model.live="kategoriFilter" class="form-select">
                                <option value="">Semua Kategori</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id_kategori }}">{{ $category->nama_kategori }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label small text-muted">Genre</label>
                            <select wire:model.live="genreFilter" class="form-select">
                                <option value="">Semua Genre</option>
                                @foreach ($genres as $genre)
                                    <option value="{{ $genre->id_genre }}">{{ $genre->nama_genre }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label small text-muted">Jenis</label>
                            <select wire:model.live="jenisFilter" class="form-select">
                                <option value="">Semua Jenis</option>
                                @foreach ($types as $type)
                                    <option value="{{ $type->id_jenis }}">{{ $type->nama_jenis }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3 d-flex align-items-end">
                            <button class="btn btn-outline-secondary w-100" wire:click="resetFilters">
                                <i class="bi bi-x-circle me-1"></i> Reset Filter
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Books Grid -->
    <div class="row g-4">
        @forelse($books as $book)
            <div class="col-md-6 col-lg-4 col-xl-3">
                <div class="card h-100 border-0 shadow-sm hover-shadow transition-all">
                    <!-- Random book cover colors -->
                    @php
                        $colors = [
                            '#3498db',
                            '#e74c3c',
                            '#2ecc71',
                            '#f39c12',
                            '#9b59b6',
                            '#1abc9c',
                            '#34495e',
                            '#d35400',
                        ];
                        $randomColor = $colors[array_rand($colors)];
                    @endphp

                    <a href="{{ route('view-buku', $book->id_buku) }}" class="text-decoration-none text-dark">
                        <div class="card-img-top d-flex align-items-center justify-content-center text-white"
                            style="height: 200px; background-color: {{ $randomColor }}">
                            <div class="text-center p-3">
                                <h5 class="mb-0 fw-bold">{{ $book->judul }}</h5>
                                <small class="opacity-75">{{ $book->penulis }}</small>
                            </div>
                        </div>
                    </a>

                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="badge bg-primary rounded-pill">{{ $book->category->nama_kategori }}</span>
                            <span class="badge bg-secondary rounded-pill">{{ $book->type->nama_jenis }}</span>
                        </div>

                        <h6 class="card-title fw-bold mb-1">{{ $book->judul }}</h6>
                        <p class="card-text text-muted small mb-2">{{ $book->penulis }} ({{ $book->tahun_terbit }})
                        </p>

                        <div class="d-flex justify-content-between align-items-center mt-3">
                            <span class="badge bg-light text-dark border">{{ $book->genre->nama_genre }}</span>
                            <span
                                class="badge {{ $book->stok > 10 ? 'bg-success' : ($book->stok > 0 ? 'bg-warning' : 'bg-danger') }}">
                                Stok: {{ $book->stok }}
                            </span>
                        </div>
                    </div>

                    <div class="card-footer bg-white border-0 pt-0">
                        <div class="d-grid gap-2">
                            @php
                                $user = auth()->user();
                                $pinjamAktif = 0;
                                $sudahDipinjam = false;

                                if ($user) {
                                    $sudahDipinjam = \App\Models\LoanHistory::where('id_user', $user->id)
                                        ->where('id_buku', $book->id_buku)
                                        ->whereNull('tanggal_kembali')
                                        ->exists();
                                }

                                $pinjamanAktif = \App\Models\LoanHistory::where('id_user', $user->id)
                                    ->whereNull('tanggal_kembali')
                                    ->count();

                                $tidakBisaPinjam = $sudahDipinjam || $pinjamanAktif >= 2;
                            @endphp

                            @if ($user)
                                @if ($sudahDipinjam)
                                    <button type="submit" wire:click="kembalikanBuku({{ $book->id_buku }})"
                                        class="btn btn-sm btn-outline-danger fw-bold">
                                        Kembalikan Buku
                                    </button>
                                @else
                                    <button type="submit" wire:click="pinjamBuku({{ $book->id_buku }})"
                                        class="btn btn-sm btn-outline-success fw-bold"
                                        @if ($tidakBisaPinjam) disabled @endif>
                                        @if ($pinjamanAktif >= 2)
                                            Maks. 2 Buku Dipinjam
                                        @else
                                            Pinjam Sekarang
                                        @endif
                                    </button>
                                @endif
                            @else
                                <a href="{{ route('login') }}" class="btn btn-sm btn-outline-primary fw-bold">
                                    Login untuk Pinjam
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="alert alert-info text-center py-5">
                    <i class="bi bi-book display-1 d-block mb-3"></i>
                    <h4>Tidak ada buku yang ditemukan</h4>
                    <p class="mb-0">Coba ubah filter atau kata kunci pencarian Anda</p>
                </div>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    <div class="row mt-4">
        <div class="col-12 d-flex justify-content-center">
            {{ $books->links('pagination::bootstrap-5') }}
        </div>
    </div>

    <!-- Add custom styles for Tailwind-like appearance -->
    <style>
        .hover-shadow:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1) !important;
        }

        .transition-all {
            transition: all 0.3s ease;
        }

        /* Custom pagination styling */
        .pagination {
            gap: 5px;
        }

        .page-item .page-link {
            border-radius: 8px;
            border: none;
            color: #6c757d;
            padding: 8px 16px;
        }

        .page-item.active .page-link {
            background-color: #0d6efd;
            color: white;
        }

        /* Custom form controls */
        .form-control,
        .form-select {
            padding: 10px 15px;
            border-radius: 8px;
        }

        .form-control:focus,
        .form-select:focus {
            box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.15);
        }

        /* Custom button styling */
        .btn {
            padding: 8px 16px;
            border-radius: 8px;
            transition: all 0.2s;
        }

        .btn:hover {
            transform: translateY(-2px);
        }
    </style>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
            document.addEventListener('livewire:initialized', function() {
                // Untuk Livewire 3, gunakan event listener yang benar
                Livewire.on('showAlertPinjam', (data) => {
                    Swal.fire({
                        icon: data[0].type,
                        title: data[0].type === 'success' ? 'Berhasil!' : 'Perhatian!',
                        text: data[0].message,
                        timer: 3000,
                        showConfirmButton: true
                    });
                });
            });
        </script>
        <script>
            document.addEventListener('livewire:initialized', function() {
                // Untuk Livewire 3, gunakan event listener yang benar
                Livewire.on('showAlertKembali', (data) => {
                    Swal.fire({
                        icon: data[0].type,
                        title: data[0].type === 'success' ? 'Berhasil!' : 'Perhatian!',
                        text: data[0].message,
                        timer: 3000,
                        showConfirmButton: true
                    });
                });
            });
        </script>
    @endpush




</div>
