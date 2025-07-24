<div>
    {{-- Header --}}
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">Laporan Taman Baca Balarea</h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Laporan</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    {{-- Filter Section --}}
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col">
                            <h4 class="card-title mb-0">Filter Laporan</h4>
                        </div>
                        <div class="col-auto">
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                data-bs-target="#printModal">
                                <i class="ri-printer-line align-middle me-1"></i> Cetak Laporan
                            </button>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <label class="form-label">Pencarian</label>
                            <input type="text" class="form-control"
                                placeholder="Cari nama, email, atau judul buku..." wire:model.live="search">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Status</label>
                            <select class="form-select" wire:model.live="statusFilter">
                                <option value="">Semua Status</option>
                                <option value="pending">Pending</option>
                                <option value="dipinjam">Dipinjam</option>
                                <option value="dikembalikan">Dikembalikan</option>
                                <option value="terlambat">Terlambat</option>
                                <option value="selesai">Selesai</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Tanggal Mulai</label>
                            <input type="date" class="form-control" wire:model.live="startDate">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Tanggal Akhir</label>
                            <input type="date" class="form-control" wire:model.live="endDate">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Per Halaman</label>
                            <select class="form-select" wire:model.live="perPage">
                                <option value="10">10</option>
                                <option value="25">25</option>
                                <option value="50">50</option>
                                <option value="100">100</option>
                            </select>
                        </div>
                        <div class="col-md-1 d-flex align-items-end">
                            <button class="btn btn-outline-secondary w-100" wire:click="resetFilters">
                                <i class="uil-refresh"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Tabs Navigation --}}
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <ul class="nav nav-tabs-custom card-header-tabs border-top-0" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link {{ $activeTab == 'peminjaman' ? 'active' : '' }}"
                                wire:click="setActiveTab('peminjaman')" role="tab">
                                <i class="ri-book-open-line me-1"></i>
                                Laporan Peminjaman
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ $activeTab == 'pengembalian' ? 'active' : '' }}"
                                wire:click="setActiveTab('pengembalian')" role="tab">
                                <i class="ri-book-mark-line me-1"></i>
                                Laporan Pengembalian
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ $activeTab == 'denda' ? 'active' : '' }}"
                                wire:click="setActiveTab('denda')" role="tab">
                                <i class="ri-money-dollar-circle-line me-1"></i>
                                Laporan Denda
                            </a>
                        </li>
                    </ul>
                </div>
                <div class="card-body">
                    <div class="tab-content">
                        {{-- Tab Peminjaman --}}
                        @if ($activeTab == 'peminjaman')
                            <div class="tab-pane fade show active">
                                <div class="table-responsive">
                                    <table class="table table-bordered table-striped align-middle">
                                        <thead class="table-primary">
                                            <tr>
                                                <th>No</th>
                                                <th>Peminjam</th>
                                                <th>Judul Buku</th>
                                                <th>Tanggal Pinjam</th>
                                                <th>Tanggal Kembali</th>
                                                <th>Status</th>
                                                <th>Denda</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($laporanPeminjaman as $index => $loan)
                                                <tr>
                                                    <td>{{ $laporanPeminjaman->firstItem() + $index }}</td>
                                                    <td>
                                                        <div class="d-flex align-items-center">
                                                            {{-- <div class="avatar-xs me-2">
                                                                <span
                                                                    class="avatar-title rounded-circle bg-primary-subtle text-primary">
                                                                    {{ substr($loan->user->name ?? 'N/A', 0, 1) }}
                                                                </span>
                                                            </div> --}}
                                                            <img src="{{ $loan->user->avatar ? asset('storage/' . $loan->user->avatar) : asset('assets/img/user.png') }}"
                                                                class="rounded-circle me-2" width="40"
                                                                height="40" style="object-fit: cover;"
                                                                alt="Avatar">
                                                            <div>
                                                                <h6 class="mb-0">{{ $loan->user->name ?? 'N/A' }}
                                                                </h6>
                                                                <small
                                                                    class="text-muted">{{ $loan->user->email ?? 'N/A' }}</small>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <h6 class="mb-0">
                                                            {{ $loan->book->judul ?? 'Buku tidak ditemukan' }}</h6>
                                                        <small
                                                            class="text-muted">{{ $loan->book->penulis ?? '' }}</small>
                                                    </td>
                                                    <td>{{ \Carbon\Carbon::parse($loan->tanggal_pinjam)->locale('id')->format('d/m/Y H:i') }}
                                                    </td>
                                                    <td>
                                                        @if ($loan->tanggal_kembali)
                                                            {{ \Carbon\Carbon::parse($loan->tanggal_kembali)->format('d/m/Y H:i') }}
                                                        @else
                                                            <span class="text-muted">Belum dikembalikan</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if ($loan->status == 'pending')
                                                            <span class="badge bg-warning">Pending</span>
                                                        @elseif($loan->status == 'dipinjam')
                                                            <span class="badge bg-info">Dipinjam</span>
                                                        @elseif($loan->status == 'dikembalikan')
                                                            <span class="badge bg-success">Dikembalikan</span>
                                                        @elseif($loan->status == 'ditolak')
                                                            <span class="badge bg-danger">Ditolak</span>
                                                        @elseif($loan->status == 'selesai')
                                                            <span class="badge bg-success">Selesai</span>
                                                        @elseif($loan->status == 'terlambat')
                                                            <span class="badge bg-danger">Terlambat</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if ($loan->denda > 0)
                                                            <div>
                                                                <span class="fw-bold text-danger">Rp
                                                                    {{ number_format($loan->denda, 0, ',', '.') }}</span>
                                                                @if ($loan->denda_dibayar)
                                                                    <br><small
                                                                        class="badge bg-success-subtle text-success">Lunas</small>
                                                                @else
                                                                    <br><small
                                                                        class="badge bg-danger-subtle text-danger">Belum
                                                                        Bayar</small>
                                                                @endif
                                                            </div>
                                                        @else
                                                            <span class="text-muted">-</span>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="7" class="text-center py-4">
                                                        <div class="text-muted">
                                                            <i class="ri-inbox-line fs-1 d-block mb-2"></i>
                                                            Tidak ada data peminjaman
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                                {{ $laporanPeminjaman->links() }}
                            </div>
                        @endif

                        {{-- Tab Pengembalian --}}
                        @if ($activeTab == 'pengembalian')
                            <div class="tab-pane fade show active">
                                <div class="table-responsive">
                                    <table class="table table-bordered table-striped align-middle">
                                        <thead class="table-success">
                                            <tr>
                                                <th>No</th>
                                                <th>Peminjam</th>
                                                <th>Judul Buku</th>
                                                <th>Tanggal Pinjam</th>
                                                <th>Tanggal Kembali</th>
                                                <th>Lama Pinjam</th>
                                                <th>Kondisi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($laporanPengembalian as $index => $loan)
                                                <tr>
                                                    <td>{{ $laporanPengembalian->firstItem() + $index }}</td>
                                                    <td>
                                                        <div class="d-flex align-items-center">
                                                            <div class="avatar-xs me-2">
                                                                <span
                                                                    class="avatar-title rounded-circle bg-success-subtle text-success">
                                                                    {{ substr($loan->user->name ?? 'N/A', 0, 1) }}
                                                                </span>
                                                            </div>
                                                            <div>
                                                                <h6 class="mb-0">{{ $loan->user->name ?? 'N/A' }}
                                                                </h6>
                                                                <small
                                                                    class="text-muted">{{ $loan->user->email ?? 'N/A' }}</small>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <h6 class="mb-0">
                                                            {{ $loan->book->judul ?? 'Buku tidak ditemukan' }}</h6>
                                                        <small
                                                            class="text-muted">{{ $loan->book->penulis ?? '' }}</small>
                                                    </td>
                                                    <td>{{ \Carbon\Carbon::parse($loan->tanggal_pinjam)->format('d/m/Y H:i') }}
                                                    </td>
                                                    <td>{{ \Carbon\Carbon::parse($loan->tanggal_kembali)->format('d/m/Y H:i') }}
                                                    </td>
                                                    <td>
                                                        @php
                                                            $lamaPinjam = \Carbon\Carbon::parse(
                                                                $loan->tanggal_pinjam,
                                                            )->diffInDays(
                                                                \Carbon\Carbon::parse($loan->tanggal_kembali),
                                                            );
                                                        @endphp
                                                        {{ $lamaPinjam }} hari
                                                    </td>
                                                    <td>
                                                        @if ($loan->denda > 0)
                                                            <span class="badge bg-warning">Terlambat</span>
                                                        @else
                                                            <span class="badge bg-success">Tepat Waktu</span>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="7" class="text-center py-4">
                                                        <div class="text-muted">
                                                            <i class="ri-inbox-line fs-1 d-block mb-2"></i>
                                                            Tidak ada data pengembalian
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                                {{ $laporanPengembalian->links() }}
                            </div>
                        @endif

                        {{-- Tab Denda --}}
                        @if ($activeTab == 'denda')
                            <div class="tab-pane fade show active">
                                <div class="table-responsive">
                                    <table class="table table-bordered table-striped align-middle">
                                        <thead class="table-danger">
                                            <tr>
                                                <th>No</th>
                                                <th>Peminjam</th>
                                                <th>Judul Buku</th>
                                                <th>Tanggal Pinjam</th>
                                                {{-- <th>Keterlambatan</th> --}}
                                                <th>Jumlah Denda</th>
                                                <th>Status Bayar</th>
                                                {{-- <th>Aksi</th> --}}
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($laporanDenda as $index => $loan)
                                                <tr>
                                                    <td>{{ $laporanDenda->firstItem() + $index }}</td>
                                                    <td>
                                                        <div class="d-flex align-items-center">
                                                            <div class="avatar-xs me-2">
                                                                <span
                                                                    class="avatar-title rounded-circle bg-danger-subtle text-danger">
                                                                    {{ substr($loan->user->name ?? 'N/A', 0, 1) }}
                                                                </span>
                                                            </div>
                                                            <div>
                                                                <h6 class="mb-0">{{ $loan->user->name ?? 'N/A' }}
                                                                </h6>
                                                                <small
                                                                    class="text-muted">{{ $loan->user->email ?? 'N/A' }}</small>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <h6 class="mb-0">
                                                            {{ $loan->book->judul ?? 'Buku tidak ditemukan' }}</h6>
                                                        <small
                                                            class="text-muted">{{ $loan->book->penulis ?? '' }}</small>
                                                    </td>
                                                    <td>{{ \Carbon\Carbon::parse($loan->tanggal_pinjam)->format('d/m/Y H:i') }}
                                                    </td>
                                                        @php
                                                            $fineInfo = $loan->getFineInfo();
                                                        @endphp
                                                    {{-- <td>
                                                        <span
                                                            class="text-danger fw-bold">{{ $fineInfo['seconds_overdue'] }}
                                                            detik</span>
                                                        <br><small class="text-muted">Batas:
                                                            {{ $fineInfo['due_date'] }}</small>
                                                    </td> --}}
                                                    <td>
                                                        <span class="fw-bold text-danger fs-6">Rp
                                                            {{ number_format($loan->denda, 0, ',', '.') }}</span>
                                                        <br><small class="text-muted">@ Rp
                                                            {{ number_format($fineInfo['fine_per_book'], 0, ',', '.') }}/buku</small>
                                                    </td>
                                                    <td>
                                                        @if ($loan->denda_dibayar)
                                                            <span class="badge bg-success">Lunas</span>
                                                        @else
                                                            <span class="badge bg-danger">Belum Bayar</span>
                                                        @endif
                                                    </td>
                                                    {{-- <td>
                                                        @if (!$loan->denda_dibayar)
                                                            <button class="btn btn-sm btn-success"
                                                                wire:click="bayarDenda({{ $loan->id_pinjaman }})">
                                                                <i class="ri-money-dollar-circle-line me-1"></i>Bayar
                                                            </button>
                                                        @else
                                                            <span class="text-success">
                                                                <i class="ri-check-line"></i> Lunas
                                                            </span>
                                                        @endif
                                                    </td> --}}
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="8" class="text-center py-4">
                                                        <div class="text-muted">
                                                            <i class="ri-inbox-line fs-1 d-block mb-2"></i>
                                                            Tidak ada data denda
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                                {{ $laporanDenda->links() }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal Cetak Laporan --}}
    <div class="modal fade" id="printModal" tabindex="-1" aria-labelledby="printModalLabel" aria-hidden="true"
        wire:ignore.self>
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="printModalLabel">Cetak Laporan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form wire:submit.prevent="cetakLaporan">
                    <div class="modal-body">
                        <div class="row g-3">
                            <div class="col-12">
                                <label class="form-label">Jenis Laporan</label>
                                <select class="form-select" wire:model="printType" required>
                                    <option value="peminjaman">Laporan Peminjaman</option>
                                    <option value="pengembalian">Laporan Pengembalian</option>
                                    <option value="denda">Laporan Denda</option>
                                </select>
                            </div>
                            <div class="col-6">
                                <label class="form-label">Tanggal Mulai</label>
                                <input type="date" class="form-control" wire:model="printStartDate" required>
                                @error('printStartDate')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="col-6">
                                <label class="form-label">Tanggal Akhir</label>
                                <input type="date" class="form-control" wire:model="printEndDate" required>
                                @error('printEndDate')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="col-12">
                                <label class="form-label">Filter Status (Opsional)</label>
                                <select class="form-select" wire:model="printStatus">
                                    <option value="">Semua Status</option>
                                    <option value="pending">Pending</option>
                                    <option value="dipinjam">Dipinjam</option>
                                    <option value="dikembalikan">Dikembalikan</option>
                                    <option value="terlambat">Terlambat</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">
                            <i class="ri-printer-line me-1"></i>Cetak Laporan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('livewire:init', () => {
                // Auto refresh overdue loans
                setInterval(() => {
                    @this.call('checkOverdueLoans');
                }, 30000); // Check every 30 seconds
            });
        </script>
    @endpush
</div>
