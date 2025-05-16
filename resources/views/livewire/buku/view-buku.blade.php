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
                                @php
                                    $user = auth()->user();
                                    $sudahDipinjam = false;

                                    if ($user) {
                                        $sudahDipinjam = \App\Models\LoanHistory::where('id_user', $user->id)
                                            ->where('id_buku', $buku->id_buku)
                                            ->whereNull('tanggal_kembali')
                                            ->exists();
                                    }
                                @endphp

                                @if ($user)
                                    @if ($sudahDipinjam)
                                        <button type="submit" wire:click="kembalikanBuku({{ $buku->id_buku }})"
                                            class="btn btn-sm btn-outline-danger fw-bold">
                                            Kembalikan Buku
                                        </button>
                                    @else
                                        <button type="submit" wire:click="pinjamBuku({{ $buku->id_buku }})"
                                            class="btn btn-sm btn-outline-success fw-bold">
                                            Pinjam Sekarang
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
            </div>
        </div>
    </div>
