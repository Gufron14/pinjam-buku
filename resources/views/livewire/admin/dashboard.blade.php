<div>
    <div class="row">
        <div class="col">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex">
                        <div class="me-3">
                            <i class="fas fa-book-open fa-2x text-warning"></i>
                        </div>
                        <div>
                            <h4 class="text-warning">{{ $dipinjam }}</h4>
                            <span class="text-muted">Dipinjam</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex">
                        <div class="me-3">
                            <i class="fas fa-check-circle fa-2x text-success"></i>
                        </div>
                        <div>
                            <h4 class="text-success">{{ $dikembalikan }}</h4>
                            <span class="text-muted">Dikembalikan</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex">
                        <div class="me-3">
                            <i class="fas fa-books fa-2x text-primary"></i>
                        </div>
                        <div>
                            <h4 class="text-primary">{{ $bukuTersedia }}</h4>
                            <span class="text-muted">Buku Tersedia</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="mt-3 text-center">
        <h3>Dashboard Jambu TaBaBa</h3>
        <h5>Sistem Informasi Pinjam Buku Taman Baca Balarea</h5>
        <div class="d-flex gap-2 justify-content-center mx-auto mt-3">
            <a href="{{ route('kelola-buku') }}" class="btn btn-primary fw-bold">Kelola Buku</a>
            <a href="{{ route('peminjaman') }}" class="btn btn-warning fw-bold">Kelola Peminjaman</a>
        </div>
    </div>
</div>
