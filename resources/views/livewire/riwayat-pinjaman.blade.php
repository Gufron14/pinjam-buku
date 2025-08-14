<div class="">
    <div class="container-fluid p-4" style="background-color: #eefff6;">
        <div class="container text-center">
            <h1 class="fw-bold text-success">Riwayat Peminjaman Buku</h1>
            <p class="text-muted">Lihat riwayat lengkap peminjaman buku kamu</p>
        </div>
    </div>

    <div class="container">
        @if (empty($loanHistories) || count($loanHistories) == 0)
            <div class="alert alert-info" role="alert">
                <i class="bi bi-info-circle me-2"></i>
                Belum ada riwayat peminjaman buku.
            </div>
        @else
            @if ($loanHistories->first()->status == 'pending')
                <div class="mt-5">
                    <div class="alert alert-primary" role="alert">
                        <i class="bi bi-info-circle me-2"></i>Kamu baru saja mengajukan peminjaman buku
                        <strong>{{ $loanHistories->first()->book->judul }},</strong> silakan datang ke Taman Baca Balera
                        untuk
                        Disetujui Petugas dan ambil buku!
                    </div>
                </div>
            @endif
            <div class="row mt-5 mb-5">
                @foreach ($loanHistories as $index => $history)
                    <div class="col-6 mb-4">
                        <div class="card border-0 shadow-sm">
                            <div class="card-body d-flex align-items-center justify-content-between p-4">
                                <div class="col-md-7">
                                    <small>Tanggal Pinjam:
                                        {{ \Carbon\Carbon::parse($history->tanggal_pinjam)->format('d M Y') }}</small>
                                    <h5 class="fw-bold text-success my-2">{{ $history->book->judul }}</h5>
                                    @if ($history->status == 'dipinjam')
                                        <small class="text-danger">Kembalikan sebelum:
                                            {{ \Carbon\Carbon::parse($history->tanggal_pinjam)->addWeeks(2)->translatedFormat('d F Y') }}</small>
                                    @elseif ($history->status == 'selesai')
                                        <small>Dikembalikan pada:
                                            {{ \Carbon\Carbon::parse($history->tanggal_kembali)->format('d M Y') }}</small>
                                    @endif
                                </div>
                                <div class="col-md-5 text-end">
                                    @if ($history->status == 'pending')
                                        <span class="badge text-bg-secondary">Menunggu Persetujuan</span>
                                        <div>
                                            <button class="btn btn-danger btn-sm my-2"
                                                wire:click="cancelLoan({{ $history->id_pinjaman }})"
                                                wire:confirm="Apakah Anda yakin ingin membatalkan pinjaman ini?">
                                                Batal Pinjam
                                            </button>
                                        </div>
                                    @elseif ($history->status == 'dipinjam')
                                        <span class="badge text-bg-primary">Dipinjam</span>
                                        <br><br>
                                        <a href="{{ route('receipt', $history->id_pinjaman) }}"
                                            class=""><small>Cetak Struk</small></a>
                                    @elseif ($history->status == 'dikembalikan')
                                        <span class="badge text-bg-secondary">Menunggu Dikembalikan</span>
                                    @elseif ($history->status == 'terlambat')
                                        <span class="badge text-bg-danger">Terlambat</span>
                                        @if (!$history->denda_dibayar)
                                            <span class="badge text-bg-secondary">Belum Bayar</span>
                                        @endif
                                        <br> <br>
                                        <small>Denda:</small>
                                        <span
                                            class="fw-bold text-danger h5">Rp{{ number_format($history->denda, 0, ',', '.') }}</span>
                                    @elseif ($history->status == 'selesai')
                                        <span class="badge text-bg-success">Selesai</span>
                                        @if ($history->denda_dibayar)
                                            <span class="badge text-bg-primary">Sudah Bayar</span>
                                        @endif
                                        @if ($history->denda_dibayar)
                                            <br> <br>
                                            <small>Denda:</small>
                                            <span
                                                class="fw-bold text-danger h5">Rp{{ number_format($history->denda, 0, ',', '.' ?? '') }}</span>
                                        @endif
                                        <br><br>
                                        <a href="{{ route('receipt', $history->id_pinjaman) }}"
                                            class=""><small>Cetak Struk</small></a>
                                    @elseif ($history->status == 'ditolak')
                                        <span class="badge text-bg-danger">Ditolak</span>
                                    @elseif ($history->status == 'dibatalkan')
                                        <span class="badge text-bg-secondary">Dibatalkan</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>
