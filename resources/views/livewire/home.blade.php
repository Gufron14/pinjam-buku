<div>
    <div class="container col-xxl-8 px-4 py-4">
        <div class="row flex-lg-row-reverse align-items-center g-5 py-5">
            <div class="col-10 col-sm-8 col-lg-6">
                <img src="https://rencanamu.id/assets/file_uploaded/editor/1490954042-o-reading-.jpg"
                    class="d-block mx-lg-auto img-fluid" alt="baca buku" loading="lazy">
            </div>
            <div class="col-lg-6">
                <h1 class=" fw-bold lh-2 mb-3 text-success">Temukan Buku Favoritmu, Gratis & Gampang di Taman Baca
                    Balarea 📚🌿</h1>
                <p class="lead">Scroll, pilih, pinjam – tinggal gitu aja! Nikmati sensasi baca buku sambil santai di
                    taman. <b>#BalareaVibes #BacaItuKeren</b></p>
                <div class="d-grid gap-2 d-md-flex justify-content-md-start">

                    @guest
                        <a href="{{ route('login') }}" class="btn btn-success px-4 me-md-2 fw-bold">👉 Mulai Pinjam
                            Sekarang</a>
                    @endguest

                    @auth
                        <a href="{{ route('daftar-buku') }}" class="btn btn-success px-4 me-md-2 fw-bold">Cari Buku</a>
                    @endauth

                </div>
            </div>
        </div>
        <div class="mb-5">
            <h4 class="fw-bold text-success text-center mb-5">🌿 Tentang Taman Baca Balarea</h4>
            <div class="d-flex gap-5 align-items-center">
                <div class="col">
                    <img src="https://cdn.antaranews.com/cache/1200x800/2023/02/08/anak-membaca-di-TBM.jpeg"
                        alt="taman baca" width="100%">
                </div>
                <div class="col">
                    <p class="lead"><b>Taman Baca Balarea (TaBaBa)</b> adalah ruang literasi terbuka yang berdiri
                        untuk menumbuhkan budaya membaca di tengah masyarakat. Kami menyediakan akses gratis ke ratusan
                        buku untuk anak-anak, remaja, hingga dewasa. Dengan semangat gotong royong, kami ingin
                        menjadikan membaca sebagai bagian dari kehidupan sehari-hari.</p>
                </div>
            </div>
        </div>
        <div class="mb-5 text-center">
            <h4 class="fw-bold text-success text-center mb-3">🔥 Buku yang Paling Diminati</h4>
            <div class="row mb-3">
                @forelse ($buku as $item)
                    <div class="col">
                        <div class="card h-100 border-0 shadow-sm hover-shadow transition-all">
                            @php
                                $colors = [
                                    '#3498db',
                                    '#e74c3c',
                                    '#2ecc71',
                                    '#f39c12',
                                    '#9b59b6',
                                    '#1abc9c',
                                    '#34495e',
                                    '#d35400',
                                ];
                                $randomColor = $colors[array_rand($colors)];
                            @endphp

                            <div class="d-flex align-items-center justify-content-center text-white"
                                style="height: 200px; background-color: {{ $randomColor }}">
                                <div class="text-center p-3">
                                    <h5 class="mb-0 fw-bold">{{ $item->judul }} ({{ $item->tahun_terbit }})</h5>
                                    <small class="opacity-75">{{ $item->penulis }}</small>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center">
                        <p class="lead">Belum ada buku yang paling diminati saat ini.</p>
                    </div>
                @endforelse
            </div>
            <a href="{{ route('daftar-buku') }}" class="btn btn-success fw-bold">👉 Lihat Semua Buku</a>
        </div>
        <div>
            <h4 class="fw-bold text-success text-center mb-5">🤝 Mari Dukung Gerakan Literasi!</h4>
            <div class="d-flex gap-5 align-items-center">
                <div class="col">
                    <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTrBEQ92-jfpKrqoAlWMlOsoAUXzxFlTdbbzg&s"
                        alt="taman baca" width="100%">
                </div>
                <div class="col">
                    <p>Ingin ikut berkontribusi? Kamu bisa menjadi relawan, menyumbangkan buku, atau sekadar menyebarkan
                        semangat membaca. Setiap aksi kecilmu sangat berarti!</p>
                    <button class="btn btn-success fw-bold">☎️ Hubungi Kami</button>
                </div>
            </div>
        </div>
    </div>
</div>
