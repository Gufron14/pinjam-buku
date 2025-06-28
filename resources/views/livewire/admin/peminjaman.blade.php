<div>
    @if (session()->has('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i>
            <strong>Berhasil!</strong> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if (session()->has('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2"></i>
            <strong>Error!</strong> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if (session()->has('warning'))
        <div class="alert alert-warning alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-circle-fill me-2"></i>
            <strong>Peringatan!</strong> {{ session('warning') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if (session()->has('info'))
        <div class="alert alert-info alert-dismissible fade show" role="alert">
            <i class="bi bi-info-circle-fill me-2"></i>
            <strong>Info!</strong> {{ session('info') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2"></i>
            <strong>Validasi Error!</strong>
            <ul class="mb-0 mt-2">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
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
                                    <div class="text-center">
                                        <a href="{{ route('kelola-denda') }}" class="text-danger fw-bold h5">
                                            Rp{{ number_format($loan->denda, 0, ',', '.') }}
                                        </a>
                                    </div>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>


                            <td>
                                @if ($loan->status === 'pending')
                                    {{-- Jika status pending, tampilkan button setujui dan tolak --}}
                                    <button class="btn btn-primary btn-sm" data-bs-toggle="modal"
                                        data-bs-target="#setujuiModal"
                                        wire:click="setSelectedLoan({{ $loan->id_pinjaman }})">
                                        Setujui
                                    </button>
                                    <button wire:click="tolakPeminjaman({{ $loan->id_pinjaman }})"
                                        class="btn btn-danger btn-sm"
                                        wire:confirm="Apakah Anda yakin ingin menolak peminjaman ini?">
                                        Tolak
                                    </button>

                                    {{-- Dipinjam -> Bukti Pinjam --}}
                                @elseif ($loan->status === 'dipinjam')
                                    <button type="button" class="btn btn-warning btn-sm" data-bs-toggle="modal"
                                        data-bs-target="#buktiModal"
                                        wire:click="setSelectedLoan({{ $loan->id_pinjaman }})">
                                        <i class="uil-eye me-1"></i>Bukti Pinjam
                                    </button>

                                    {{-- Dikembalikan -> Konfirmasi --}}
                                @elseif ($loan->status === 'dikembalikan')
                                    <button class="btn btn-primary btn-sm" data-bs-toggle="modal"
                                        data-bs-target="#pengembalianModal"
                                        wire:click="setSelectedLoanForReturn({{ $loan->id_pinjaman }})">
                                        Konfirmasi
                                    </button>

                                    {{-- Selesai -> Bukti Kembali --}}
                                @elseif ($loan->status === 'selesai')
                                    <button class="btn btn-success btn-sm" data-bs-toggle="modal"
                                        data-bs-target="#buktiKembaliModal"
                                        wire:click="setSelectedLoanForProof({{ $loan->id_pinjaman }})">
                                        <i class="uil-eye me-1"></i>
                                        Bukti Pengembalian
                                    </button>

                                    {{-- Terlambat -> Peringati, Tandai Lunas --}}
                                @elseif ($loan->status === 'terlambat')
                                    @if ($loan->denda_dibayar)
                                        <span class="badge bg-success">Sudah Dibayar</span>
                                    @else
                                        <button wire:click="peringatiPeminjam({{ $loan->id_pinjaman }})"
                                            class="btn btn-danger btn-sm">
                                            Peringati
                                        </button>
                                        <button class="btn btn-sm btn-outline-primary mt-1"
                                            wire:click="markFineAsPaid({{ $loan->id_pinjaman }})"
                                            wire:confirm="Tandai denda sebagai sudah dibayar?">
                                            Tandai Lunas
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
                            <button type="button" class="btn btn-primary" wire:click="konfirmasiPeminjaman"
                                data-bs-dismiss="modal" wire:loading.attr="disabled">
                                <span wire:loading.remove>Setujui</span>
                                <span wire:loading>Processing...</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Modal Konfirmasi PengembaIian --}}
            <div class="modal fade" id="pengembalianModal" tabindex="-1" aria-labelledby="pengembalianModalLabel"
                aria-hidden="true" wire:ignore.self>
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h1 class="modal-title fs-5" id="pengembalianModalLabel">Unggah Bukti Pengembalian</h1>
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
                            <button type="button" class="btn btn-primary" wire:click="konfirmasiPengembalian"
                                data-bs-dismiss="modal" wire:loading.attr="disabled">
                                <span wire:loading.remove>Konfirmasi</span>
                                <span wire:loading>Processing...</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal untuk Bukti Pinjam -->
            <div class="modal fade" id="buktiModal" tabindex="-1" aria-labelledby="buktiModalLabel"
                aria-hidden="true" wire:ignore.self>
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h1 class="modal-title fs-5" id="buktiModalLabel">Bukti Peminjaman Buku</h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>
                        <div class="modal-body text-center">
                            @if ($selectedLoanId)
                                @php
                                    $selectedLoan = \App\Models\LoanHistory::find($selectedLoanId);
                                @endphp
                                @if ($selectedLoan && $selectedLoan->bukti_pinjam)
                                    <div class="mb-3">
                                        <strong>Peminjam:</strong> {{ $selectedLoan->user->name ?? 'N/A' }}<br>
                                        <strong>Buku:</strong> {{ $selectedLoan->book->judul ?? 'N/A' }}<br>
                                        <strong>Tanggal Pinjam:</strong>
                                        {{ \Carbon\Carbon::parse($selectedLoan->tanggal_pinjam)->format('d/m/Y H:i') }}
                                    </div>
                                    <img src="{{ asset('storage/' . $selectedLoan->bukti_pinjam) }}"
                                        alt="Bukti Peminjaman" class="img-fluid rounded"
                                        style="max-width: 100%; height: auto;">
                                @else
                                    <p>Bukti pinjam tidak tersedia</p>
                                    @if ($selectedLoan)
                                        <small class="text-muted">Debug: ID Loan = {{ $selectedLoanId }}, Bukti =
                                            {{ $selectedLoan->bukti_pinjam ?? 'null' }}</small>
                                    @endif
                                @endif
                            @else
                                <p>Tidak ada data yang dipilih</p>
                            @endif
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                        </div>
                    </div>
                </div>
            </div>


            <div class="modal fade" id="buktiKembaliModal" tabindex="-1" aria-labelledby="buktiModalLabel"
                aria-hidden="true" wire:ignore.self>
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h1 class="modal-title fs-5" id="buktiModalLabel">Bukti Pengembalian Buku</h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>
                        <div class="modal-body text-center">
                            @if ($selectedLoanId)
                                @php
                                    $selectedLoan = \App\Models\LoanHistory::find($selectedLoanId);
                                @endphp
                                @if ($selectedLoan && $selectedLoan->bukti_kembali)
                                                                    <div class="mb-3">
                                        <strong>Peminjam:</strong> {{ $selectedLoan->user->name ?? 'N/A' }}<br>
                                        <strong>Buku:</strong> {{ $selectedLoan->book->judul ?? 'N/A' }}<br>
                                        <strong>Tanggal Pinjam:</strong>
                                        {{ \Carbon\Carbon::parse($selectedLoan->tanggal_pinjam)->format('d/m/Y H:i') }}
                                    </div>
                                    <img src="{{ asset('storage/' . $selectedLoan->bukti_kembali) }}"
                                        alt="Bukti Kembali" class="img-fluid rounded"
                                        style="max-width: 100%; height: auto;">
                                @else
                                    <p>Bukti pengembalian buku tidak tersedia</p>
                                @endif
                            @else
                                <p>Bukti pengembalian buku tidak tersedia</p>
                            @endif
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                        </div>
                    </div>
                </div>
            </div>


        </div>
    </div>
</div>
