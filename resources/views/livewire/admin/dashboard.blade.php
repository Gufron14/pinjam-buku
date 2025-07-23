<div>
    {{-- Statistik Cards --}}
    <div class="row">
        <div class="col-xl-3 col-md-6">
            <div class="card card-animate">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <p class="text-uppercase fw-medium text-muted mb-0">Total Peminjaman</p>
                        </div>
                        <div class="flex-shrink-0">
                            <h5 class="text-success fs-14 mb-0">
                                <i class="ri-arrow-right-up-line fs-13 align-middle"></i>
                            </h5>
                        </div>
                    </div>
                    <div class="d-flex align-items-end justify-content-between mt-4">
                        <div>
                            <h4 class="fs-22 fw-semibold ff-secondary mb-4">
                                <span class="counter-value"
                                    data-target="{{ $statistik['total_peminjaman'] }}">{{ $statistik['total_peminjaman'] }}</span>
                            </h4>
                            <span class="badge bg-success-subtle text-success mb-0">
                                <i class="ri-book-line align-middle"></i> Buku
                            </span>
                        </div>
                        <div class="avatar-sm flex-shrink-0">
                            <span class="avatar-title bg-success-subtle rounded fs-3">
                                <i class="bx bx-book-open text-success"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card card-animate">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <p class="text-uppercase fw-medium text-muted mb-0">Total Pengembalian</p>
                        </div>
                        <div class="flex-shrink-0">
                            <h5 class="text-info fs-14 mb-0">
                                <i class="ri-arrow-right-up-line fs-13 align-middle"></i>
                            </h5>
                        </div>
                    </div>
                    <div class="d-flex align-items-end justify-content-between mt-4">
                        <div>
                            <h4 class="fs-22 fw-semibold ff-secondary mb-4">
                                <span class="counter-value"
                                    data-target="{{ $statistik['total_pengembalian'] }}">{{ $statistik['total_pengembalian'] }}</span>
                            </h4>
                            <span class="badge bg-info-subtle text-info mb-0">
                                <i class="ri-book-mark-line align-middle"></i> Dikembalikan
                            </span>
                        </div>
                        <div class="avatar-sm flex-shrink-0">
                            <span class="avatar-title bg-info-subtle rounded fs-3">
                                <i class="bx bx-book-bookmark text-info"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card card-animate">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <p class="text-uppercase fw-medium text-muted mb-0">Keterlambatan</p>
                        </div>
                        <div class="flex-shrink-0">
                            <h5 class="text-danger fs-14 mb-0">
                                <i class="ri-arrow-right-up-line fs-13 align-middle"></i>
                            </h5>
                        </div>
                    </div>
                    <div class="d-flex align-items-end justify-content-between mt-4">
                        <div>
                            <h4 class="fs-22 fw-semibold ff-secondary mb-4">
                                <span class="counter-value"
                                    data-target="{{ $statistik['total_terlambat'] }}">{{ $statistik['total_terlambat'] }}</span>
                            </h4>
                            <span class="badge bg-danger-subtle text-danger mb-0">
                                <i class="ri-time-line align-middle"></i> Terlambat
                            </span>
                        </div>
                        <div class="avatar-sm flex-shrink-0">
                            <span class="avatar-title bg-danger-subtle rounded fs-3">
                                <i class="bx bx-time text-danger"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card card-animate">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <p class="text-uppercase fw-medium text-muted mb-0">Total Denda</p>
                        </div>
                        <div class="flex-shrink-0">
                            <h5 class="text-warning fs-14 mb-0">
                                <i class="ri-arrow-right-up-line fs-13 align-middle"></i>
                            </h5>
                        </div>
                    </div>
                    <div class="d-flex align-items-end justify-content-between mt-4">
                        <div>
                            <h4 class="fs-22 fw-semibold ff-secondary mb-4">
                                Rp <span class="counter-value"
                                    data-target="{{ $statistik['total_denda'] }}">{{ number_format($statistik['total_denda'], 0, ',', '.') }}</span>
                            </h4>
                            <span class="badge bg-warning-subtle text-warning mb-0">
                                <i class="ri-money-dollar-circle-line align-middle"></i> Denda
                            </span>
                        </div>
                        <div class="avatar-sm flex-shrink-0">
                            <span class="avatar-title bg-warning-subtle rounded fs-3">
                                <i class="bx bx-money text-warning"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

        {{-- Buku Terpopuler --}}
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title mb-0">Buku Terpopuler</h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Ranking</th>
                                    <th>Judul Buku</th>
                                    <th>Penulis</th>
                                    <th>Total Dipinjam</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($statistik['buku_terpopuler'] as $index => $popular)
                                    <tr>
                                        <td>
                                            <span class="badge bg-primary">{{ $index + 1 }}</span>
                                        </td>
                                        <td>{{ $popular->book->judul ?? 'N/A' }}</td>
                                        <td>{{ $popular->book->penulis ?? 'N/A' }}</td>
                                        <td>
                                            <span class="badge bg-success">{{ $popular->total_pinjam }} kali</span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center text-muted">Tidak ada data</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
