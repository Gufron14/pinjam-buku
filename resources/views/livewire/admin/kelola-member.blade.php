<div>
    {{-- Alert Messages --}}
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

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h4 class="card-title mb-0">Kelola Member</h4>
        </div>
        <div class="card-body">
            {{-- Search Bar --}}
            <div class="row mb-3">
                <div class="col-md-6">
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="bi bi-search"></i>
                        </span>
                        <input type="text" class="form-control" placeholder="Cari nama, email, atau no. telepon..."
                            wire:model.live="search">
                    </div>
                </div>
            </div>

            {{-- Table --}}
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead class="table-danger">
                        <tr class="text-center">
                            <th style="width: 5%">No</th>
                            <th style="width: 20%">Nama</th>
                            {{-- <th style="width: 25%">Buku Dipinjam</th> --}}
                            <th style="width: 20%">Email</th>
                            <th style="width: 15%">No. WhatsApp</th>
                            <th style="width: 15%">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($users as $index => $user)
                            <tr>
                                <td class="text-center">{{ $users->firstItem() + $index }}</td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <img src="{{ $user->avatar ? asset('storage/' . $user->avatar) : asset('assets/img/user.png') }}"
                                            class="rounded-circle me-2" width="40" height="40"
                                            style="object-fit: cover;" alt="Avatar">
                                        <div>
                                            <strong>{{ $user->name }}</strong>
                                            <br>
                                            <small class="text-muted">{{ ucfirst($user->getUmurAttribute()) }}
                                                Tahun</small>
                                        </div>
                                    </div>
                                </td>
                                {{-- <td>
                                    @if ($user->loans->count() > 0)
                                        @foreach ($user->loans as $loan)
                                            <span class="badge bg-warning text-dark mb-1 d-block">
                                                {{ $loan->book->judul ?? 'Buku tidak ditemukan' }}
                                            </span>
                                        @endforeach
                                    @else
                                        <span class="text-muted">Tidak ada buku dipinjam</span>
                                    @endif
                                </td> --}}
                                <td>
                                    <div class="d-flex justify-content-between">
                                        {{ $user->email }}
                                        <div>
                                            @if ($user->email_verified_at)
                                                <span class="badge bg-success">
                                                    <i class="fas fa-check me-2"></i>Verified</span>
                                            @else
                                                <span class="badge bg-danger">
                                                    <i class="fas fa-times me-2"></i>Unverivied</span>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    @if ($user->no_telepon)
                                        <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $user->no_telepon) }}"
                                            target="_blank" class="text-success text-decoration-none">
                                            <i class="bi bi-whatsapp"></i> {{ $user->no_telepon }}
                                        </a>
                                    @else
                                        <span class="text-muted">Tidak ada</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <button type="button" class="btn btn-info btn-sm"
                                        wire:click="showDetail({{ $user->id }})" data-bs-toggle="modal"
                                        data-bs-target="#detailModal">
                                        <i class="bi bi-eye"></i> Detail
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-4">
                                    <div class="text-muted">
                                        <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                                        Tidak ada data member ditemukan
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            <div class="d-flex justify-content-between align-items-center mt-3">
                <div class="text-muted">
                    Menampilkan {{ $users->firstItem() ?? 0 }} - {{ $users->lastItem() ?? 0 }} dari
                    {{ $users->total() }} member
                </div>
                {{ $users->links() }}
            </div>
        </div>
    </div>

    {{-- Modal Detail User --}}
    <div class="modal fade" id="detailModal" tabindex="-1" aria-labelledby="detailModalLabel" aria-hidden="true"
        wire:ignore.self>
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="detailModalLabel">
                        <i class="bi bi-person-circle me-2"></i>Detail Member
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                        wire:click="closeModal"></button>
                </div>
                <div class="modal-body">
                    @if ($selectedUser)
                        <div class="row">
                            {{-- Profile Picture --}}
                            <div class="col-md-4 text-center mb-3">
                                <img src="{{ $selectedUser->avatar ? asset('storage/' . $selectedUser->avatar) : asset('assets/img/user.png') }}"
                                    class="rounded-circle img-fluid" width="150" height="150"
                                    style="object-fit: cover;" alt="Avatar">
                            </div>

                            {{-- User Information --}}
                            <div class="col-md-8">
                                <table class="table table-borderless">
                                    <tr>
                                        <td width="30%"><strong>Nama:</strong></td>
                                        <td>{{ $selectedUser->name }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Tanggal Lahir:</strong></td>
                                        <td>{{ $selectedUser->ttl }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Email:</strong></td>
                                        <td>{{ $selectedUser->email }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>No. Telepon:</strong></td>
                                        <td>
                                            @if ($selectedUser->no_telepon)
                                                <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $selectedUser->no_telepon) }}"
                                                    target="_blank" class="text-success text-decoration-none">
                                                    <i class="bi bi-whatsapp"></i> {{ $selectedUser->no_telepon }}
                                                </a>
                                            @else
                                                <span class="text-muted">Tidak ada</span>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><strong>Jenis Kelamin:</strong></td>
                                        <td>{{ ucfirst($selectedUser->jenis_kelamin ?? 'Tidak diset') }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Alamat:</strong></td>
                                        <td>{{ $selectedUser->alamat ?? 'Tidak ada alamat' }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Bergabung:</strong></td>
                                        <td>{{ $selectedUser->created_at->format('d M Y, H:i') }}</td>
                                    </tr>
                                </table>
                            </div>
                        </div>

                        {{-- KTP --}}
                        <div class="mt-4">
                            <h6 class="border-bottom pb-2 mb-3">
                                <i class="bi bi-id-card me-2"></i>Foto Tanda Pengenal
                            </h6>
                            @if ($selectedUser->ktp)
                                <img src="{{ asset('storage/' . $selectedUser->ktp) }}" class="img-fluid rounded"
                                    alt="Foto KTP">
                            @else
                                <div class="text-center text-muted py-3">
                                    <i class="bi bi-inbox fs-3 d-block mb-2"></i>
                                    Tidak ada foto KTP
                                </div>
                            @endif
                        </div>

                        {{-- Loan History --}}
                        {{-- <div class="mt-4">
                            <h6 class="border-bottom pb-2 mb-3">
                                <i class="bi bi-book me-2"></i>Riwayat Peminjaman
                            </h6>
                            @if ($selectedUser->loans && $selectedUser->loans->count() > 0)
                                <div class="table-responsive">
                                    <table class="table table-sm table-striped">
                                        <thead>
                                            <tr>
                                                <th>Judul Buku</th>
                                                <th>Tanggal Pinjam</th>
                                                <th>Tanggal Kembali</th>
                                                <th>Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($selectedUser->loans as $loan)
                                                <tr>
                                                    <td>{{ $loan->book->judul ?? 'Buku tidak ditemukan' }}</td>
                                                    <td>{{ \Carbon\Carbon::parse($loan->tanggal_pinjam)->format('d M Y') }}</td>
                                                    <td>
                                                        @if ($loan->tanggal_kembali)
                                                            {{ \Carbon\Carbon::parse($loan->tanggal_kembali)->format('d M Y') }}
                                                        @else
                                                            <span class="text-muted">Belum dikembalikan</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if ($loan->status == 'pinjam')
                                                            <span class="badge bg-warning">Dipinjam</span>
                                                        @elseif($loan->status == 'kembali')
                                                            <span class="badge bg-success">Dikembalikan</span>
                                                        @else
                                                            <span class="badge bg-secondary">{{ ucfirst($loan->status) }}</span>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <div class="text-center text-muted py-3">
                                    <i class="bi bi-inbox fs-3 d-block mb-2"></i>
                                    Belum ada riwayat peminjaman
                                </div>
                            @endif
                        </div> --}}
                    @endif
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"
                        wire:click="closeModal">
                        <i class="bi bi-x-circle me-1"></i>Tutup
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
    <script>
        document.addEventListener('livewire:init', () => {
            Livewire.on('show-modal', () => {
                const modal = new bootstrap.Modal(document.getElementById('detailModal'));
                modal.show();
            });
        });
    </script>
@endpush
