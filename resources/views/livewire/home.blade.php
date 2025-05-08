<div>
    <div class="container col-xxl-8 px-4 py-4">
        <div class="row flex-lg-row-reverse align-items-center g-5 py-5">
            <div class="col-10 col-sm-8 col-lg-6">
                <img src="https://rencanamu.id/assets/file_uploaded/editor/1490954042-o-reading-.jpg" class="d-block mx-lg-auto img-fluid" alt="baca buku" loading="lazy">
            </div>
            <div class="col-lg-6">
                <h2 class=" fw-bold lh-2 mb-3">Temukan Buku Favoritmu, Gratis & Gampang di Taman Baca Balarea 📚🌿</h2>
                <p class="lead">Scroll, pilih, pinjam – tinggal gitu aja! Nikmati sensasi baca buku sambil santai di taman. <b>#BalareaVibes #BacaItuKeren</b></p>
                <div class="d-grid gap-2 d-md-flex justify-content-md-start">
                    
                    @guest   
                        <a href="{{ route('login') }}" class="btn btn-primary btn-lg px-4 me-md-2 fw-bold">👉 Mulai Pinjam Sekarang</a>
                    @endguest

                    @auth
                        <a href="{{ route('daftar-buku') }}" class="btn btn-primary btn-lg px-4 me-md-2 fw-bold">Daftar Buku</a>
                    @endauth
                    
                </div>
            </div>
        </div>
    </div>
</div>
