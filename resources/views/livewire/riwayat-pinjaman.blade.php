<div class="container py-4">
    <h1 class="mb-4">Riwayat Peminjaman Buku</h1>

    @if(empty($loanHistories) || count($loanHistories) == 0)
        <div class="alert alert-info" role="alert">
            <i class="bi bi-info-circle me-2"></i>
            Belum ada riwayat peminjaman buku.
        </div>
    @else
        <div class="table-responsive">
            <table class="table table-striped table-bordered">
                <thead class="table-light">
                    <tr>
                        <th>No</th>
                        <th>Judul Buku</th>
                        <th>Kategori</th>
                        <th>Tanggal Pinjam</th>
                        <th>Tanggal Kembali</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($loanHistories as $index => $history)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $history->book->judul }}</td>
                            <td>{{ $history->book->category->nama_kategori ?? 'N/A' }}</td>
                            <td>{{ \Carbon\Carbon::parse($history->tanggal_pinjam)->format('d M Y') }}</td>
                            <td>{{ $history->tanggal_kembali ? \Carbon\Carbon::parse($history->tanggal_kembali)->format('d M Y') : 'Belum dikembalikan' }}</td>
                            <td>
                                @if($history->status == 'dipinjam')
                                    <span class="badge bg-primary">Dipinjam</span>
                                @elseif($history->status == 'dikembalikan')
                                    <span class="badge bg-success">Dikembalikan</span>
                                @elseif($history->status == 'terlambat')
                                    <span class="badge bg-danger">Terlambat</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        @if(method_exists($loanHistories, 'links'))
            <div class="mt-4">
                {{ $loanHistories->links() }}
            </div>
        @endif
    @endif
</div>
