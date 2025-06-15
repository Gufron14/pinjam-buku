<div class="container py-4">
    <h1 class="mb-4 fw-bold text-success">Riwayat Peminjaman Buku</h1>

    @if (empty($loanHistories) || count($loanHistories) == 0)
        <div class="alert alert-info" role="alert">
            <i class="bi bi-info-circle me-2"></i>
            Belum ada riwayat peminjaman buku.
        </div>
    @else
        <div class="row">
            @foreach ($loanHistories as $index => $history)
                @if ($history->status == 'pending')
                <div class="container">
                    <div class="alert alert-primary" role="alert">
                        <i class="bi bi-info-circle me-2"></i>Kamu sudah minjam buku <strong>{{ $history->book->judul }},</strong> silakan datang ke Taman Baca Balera untuk Disetujui Petugas dan ambil buku!
                    </div>
                </div>
                @endif
                <div class="col-6">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body d-flex align-items-center justify-content-between">
                            <div class="col">
                                <small>Tanggal Pinjam:
                                    {{ \Carbon\Carbon::parse($history->tanggal_pinjam)->format('d M Y') }}</small>
                                <h5 class="fw-bold text-success my-2">{{ $history->book->judul }}</h5>
                                @if ($history->status == 'dipinjam')
                                    <small class="text-danger">Harus dikembalikan pada:</small>
                                @elseif ($history->status == 'selesai')
                                    <small>Dikembalikan pada:</small>
                                @endif
                            </div>
                            <div class="col text-end">
                                @if ($history->status == 'pending')
                                    <span class="badge text-bg-secondary">Menunggu Persetujuan</span>
                                    <div>
                                        <button class="btn btn-danger btn-sm my-2">
                                            Batalkan
                                        </button>
                                    </div>
                                @elseif ($history->status == 'dipinjam')
                                    <span class="badge text-bg-primary">Dipinjam</span>
                                @elseif ($history->status == 'dikembalikan')
                                    <span class="badge text-bg-warning">Menunggu Persetujuan</span>
                                @elseif ($history->status == 'terlambat')
                                    <span class="badge text-bg-danger">Terlambat</span>
                                @elseif ($history->status == 'selesai')
                                    <span class="badge text-bg-success">Selesai</span>
                                @elseif ($history->status == 'ditolak')
                                    <span class="badge text-bg-danger">Ditolak</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif



</div>
