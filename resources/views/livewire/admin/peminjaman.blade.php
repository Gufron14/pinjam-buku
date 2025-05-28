<div>
    @if (session()->has('message'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('message') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card">
        <div class="card-header">
            <h4 class="card-title">Daftar Peminjaman Buku</h4>
        </div>
        <div class="card-body">
            <table class="table table-bordered">
                <thead>
                    <tr class="text-center">
                        <th>No</th>
                        <th>Tanggal Pinjam</th>
                        <th>Judul Buku</th>
                        <th>Nama Peminjam</th>
                        <th>Status</th>
                        <th>Denda</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($loans as $index => $loan)
                        <tr class="text-center">
                            <td>{{ $loans->firstItem() + $index }}</td>
                            <td>{{ \Carbon\Carbon::parse($loan->tanggal_pinjam)->format('d/m/Y') }}</td>
                            <td>{{ $loan->book->judul ?? 'Buku tidak ditemukan' }}</td>
                            <td>{{ $loan->user->name ?? 'User tidak ditemukan' }}</td>
                            <td>{!! $this->getStatusBadge($loan->status) !!}</td>
                            <td>
                                @if ($loan->denda > 0)
                                    Rp {{ number_format($loan->denda, 0, ',', '.') }}
                                    @if ($loan->denda_dibayar && !$loan->konfirmasi_admin)
                                        <br><small class="text-warning">Menunggu Konfirmasi</small>
                                    @elseif($loan->denda_dibayar && $loan->konfirmasi_admin)
                                        <br><small class="text-success">Sudah Dibayar</small>
                                    @else
                                        <br><small class="text-danger">Belum Dibayar</small>
                                    @endif
                                @else
                                    -
                                @endif
                            </td>
                            <td>
                                @if (!$loan->konfirmasi_admin && $loan->status === 'pending')
                                    {{-- Jika belum disetujui, tampilkan button-button berikut --}}
                                    <button class="btn btn-primary btn-sm" data-bs-toggle="modal"
                                        data-bs-target="#setujuiModal">
                                        Setujui
                                    </button>
                                    <button wire:click="tolakPeminjaman({{ $loan->id_pinjaman }})"
                                        class="btn btn-danger btn-sm"
                                        wire:confirm="Apakah Anda yakin ingin menolak peminjaman ini?">
                                        Tolak
                                    </button>
                                @else
                                    {{-- Jika sudah disetujui, tampilkan button-button berikut --}}
                                    @if ($loan->status === 'dipinjam')
                                        <button type="button" class="btn btn-warning btn-sm" data-bs-toggle="modal"
                                            data-bs-target="#buktiModal"
                                            wire:click="lihatBuktiPembayaran({{ $loan->id_pinjaman }})">
                                            <i class="uil-eye me-1"></i>Lihat Bukti
                                        </button>
                                    @endif

                                    @if ($loan->status === 'terlambat' || $loan->isOverdue())
                                        <button wire:click="peringatiPeminjam({{ $loan->id_pinjaman }})"
                                            class="btn btn-danger btn-sm">
                                            Peringati
                                        </button>
                                    @endif

                                    @if ($loan->status === 'dikembalikan')
                                        <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal"
                                            data-bs-target="#pengembalianModal">
                                            Konfirmasi
                                        </button>
                                    @endif
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center">Tidak ada data peminjaman</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            {{ $loans->links() }}

            <!-- Modal Setuju-->
            <div class="modal fade" id="setujuiModal" tabindex="-1" aria-labelledby="exampleModalLabel"
                aria-hidden="true" wire:ignore.self>
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h1 class="modal-title fs-5" id="exampleModalLabel">Unggah Bukti Pinjam</h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <input type="file" class="form-control" wire:model="bukti_pinjam" accept="image/*"
                                required>
                            @error('bukti_pinjam')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                            <button type="button" class="btn btn-primary"
                                wire:click="setujuiPeminjaman({{ $loan->id_pinjaman ?? 0 }})" data-bs-dismiss="modal"
                                wire:loading.attr="disabled">
                                <span wire:loading.remove>Setujui</span>
                                <span wire:loading>Processing...</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Modal Konfirmasi PengembaIian --}}
            <div class="modal fade" id="pengembalianModal" tabindex="-1" aria-labelledby="exampleModalLabel"
                aria-hidden="true" wire:ignore.self>
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h1 class="modal-title fs-5" id="exampleModalLabel">Unggah Bukti Pengembalian</h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <input type="file" class="form-control" wire:model="bukti_kembali" accept="image/*"
                                required>
                            @error('bukti_kembali')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                            <button type="button" class="btn btn-primary"
                                wire:click="konfirmasiPengembalian({{ $loan->id_pinjaman ?? 0 }})" data-bs-dismiss="modal"
                                wire:loading.attr="disabled">
                                <span wire:loading.remove>Konfirmasi</span>
                                <span wire:loading>Processing...</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal untuk Bukti Pinjam -->
            <div class="modal fade" id="buktiModal" tabindex="-1" aria-labelledby="buktiModalLabel" aria-hidden="true"
                wire:ignore.self>
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h1 class="modal-title fs-5" id="buktiModalLabel">Bukti Peminjaman Buku</h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>
                        <div class="modal-body text-center">
                            @if ($bukti_pinjam)
                                class="img-fluid" style="max-height: 500px;">
                            @else
                                <p>Bukti pinjam tidak tersedia</p>
                            @endif
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                            @if ($selectedLoanId)
                                <button type="button" class="btn btn-primary" wire:click="konfirmasiPembayaran"
                                    data-bs-dismiss="modal">
                                    Konfirmasi Pembayaran
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
