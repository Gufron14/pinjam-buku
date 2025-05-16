<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-lg border-0 rounded-2 p-3">
                <div class="row g-0">
                    <div class="col-md-12">
                        <div class="card-body">
                            <h4 class="card-title mb-4">Detail Buku</h4>
                            <table class="table table-bordered">
                                <tbody>
                                    <tr>
                                        <th width="30%">Judul</th>
                                        <td>{{ $buku->judul }}</td>
                                    </tr>
                                    <tr>
                                        <th>Penulis</th>
                                        <td>{{ $buku->penulis }}</td>
                                    </tr>
                                    <tr>
                                        <th>Tahun Terbit</th>
                                        <td>{{ $buku->tahun_terbit }}</td>
                                    </tr>
                                    <tr>
                                        <th>Kategori</th>
                                        <td>{{ $buku->category->nama_kategori ?? 'Tidak ada kategori' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Genre</th>
                                        <td>{{ $buku->genre->nama_genre ?? 'Tidak ada genre' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Jenis</th>
                                        <td>{{ $buku->type->nama_jenis ?? 'Tidak ada jenis' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Stok</th>
                                        <td>{{ $buku->stok }}</td>
                                    </tr>
                                </tbody>
                            </table>
                            <div class="d-flex justify-content-between">
                                <a href="{{ route('daftar-buku') }}" class="btn btn-secondary">Kembali</a>
                                @auth
                                    @php
                                        $user = auth()->user();
                                        $pinjamAktif = 0;
                                        $sudahDipinjam = false;

                                        if ($user) {
                                            $sudahDipinjam = \App\Models\LoanHistory::where('id_user', $user->id)
                                                ->where('id_buku', $buku->id_buku)
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
                                            <button type="submit" wire:click="kembalikanBuku({{ $buku->id_buku }})"
                                                class="btn btn-sm btn-outline-danger fw-bold">
                                                Kembalikan Buku
                                            </button>
                                        @else
                                            <button type="submit" wire:click="pinjamBuku({{ $buku->id_buku }})"
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
                                @endauth

                                @guest
                                    <a href="{{ route('login') }}" class="btn btn-sm btn-outline-success fw-bold">
                                        Login untuk Pinjam
                                    </a>
                                @endguest
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

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
