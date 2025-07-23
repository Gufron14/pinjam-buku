<div>
    <div class="container-fluid p-3" style="background-color: #b92020;">
        <div class="container text-center">
            <h1 class="fw-bold text-white">Anda Didenda!</h1>
            <p class="text-light">Karena terlambat mengembalikan buku</p>
        </div>
    </div>
    <div class="container p-3">
        <!-- Alert Info -->
        <div class="row">
            <div class="col-12">
                <div class="alert alert-warning alert-dismissible fade show" role="alert">
                    <i class="ri-alert-line me-2"></i>
                    <strong>Perhatian!</strong> Anda memiliki denda yang belum dibayar. Silakan bayar denda terlebih
                    dahulu untuk dapat mengakses fitur lainnya.
                </div>
            </div>
        </div>

        <!-- Flash Messages -->
        @if (session()->has('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="ri-check-line me-2"></i>
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if (session()->has('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="ri-error-warning-line me-2"></i>
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <!-- Denda Table -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="ri-list-check me-2"></i>Detail Denda Buku Terlambat
                        </h5>
                    </div>
                    <div class="card-body">
                        @if (count($dendaData) > 0)
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped">
                                    <thead class="table-danger">
                                        <tr class="text-center">
                                            <th style="width: 5%">No</th>
                                            <th style="width: 25%">Judul Buku</th>
                                            <th style="width: 15%">Tgl Pinjam</th>
                                            <th style="width: 15%">Batas Kembali</th>
                                            <th style="width: 15%">Keterlambatan</th>
                                            <th style="width: 15%">Denda</th>
                                            {{-- <th style="width: 10%">Aksi</th> --}}
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($dendaData as $index => $denda)
                                            <tr>
                                                <td class="text-center">{{ $index + 1 }}</td>
                                                <td>
                                                    <strong>{{ $denda['judul_buku'] }}</strong>
                                                </td>
                                                <td class="text-center">{{ $denda['tanggal_pinjam'] }}</td>
                                                <td class="text-center">
                                                    <span class="text-danger">{{ $denda['tanggal_kembali'] }}</span>
                                                </td>
                                                <td class="text-center">
                                                    <span class="badge bg-danger">
                                                        {{ $denda['overdue_days'] }} hari,
                                                        {{ $denda['overdue_hours'] }} jam,
                                                        {{ $denda['overdue_minutes'] }} menit
                                                    </span>
                                                </td>
                                                <td class="text-center">
                                                    <span
                                                        class="fw-bold text-danger h5">Rp{{ number_format($denda['denda'], 0, ',', '.') }}
                                                    </span>
                                                </td>
                                                {{-- <td class="text-center">
                                                        <a href="#" class="btn btn-primary btn-sm">Bayar</a>
                                                </td> --}}
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <p class="text-center">Tidak ada denda yang perlu dibayar.</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Summary Card -->
        <div class="row">
            <div class="col-md-6">
                <div class="card border-danger">
                    <div class="card-body text-center">
                        <i class="ri-money-dollar-circle-line text-danger" style="font-size: 3rem;"></i>
                        <h3 class="text-danger mt-2">Rp {{ number_format($totalDenda, 0, ',', '.') }}</h3>
                        <p class="text-muted mb-0">Total Denda yang Harus Dibayar</p>
                        <small class="text-muted">{{ count($dendaData) }} buku terlambat</small>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card border-info">
                    <div class="card-body">
                        <h5 class="card-title text-info">
                            <i class="ri-information-line me-2"></i>Informasi Penting
                        </h5>
                        <ul class="mb-0">
                            <li>
                                <i class="ri-check-line text-success me-2"></i>
                                Bayar denda untuk mengakses fitur lainnya
                            </li>
                            <li>
                                <i class="ri-check-line text-success me-2"></i>
                                Denda tetap Rp 5.000 per buku terlambat
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
