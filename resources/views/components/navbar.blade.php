<nav class="navbar navbar-expand-lg bg-body-tertiary">
    <div class="container p-2">
        <a class="navbar-brand fw-bold" href="{{ route('/') }}"> <span class="me-2"><img src="{{ asset('assets/img/read.png') }}" alt="" width="30px"></span> Jambu TaBaBa</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent"
            aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav mx-auto mb-2 mb-lg-0">
                <x-nav-link :active="request()->routeIs('/')" href="{{ route('/') }}">Beranda</x-nav-link>
                <x-nav-link :active="request()->routeIs('daftar-buku')" href="{{ route('daftar-buku') }}">Daftar Buku</x-nav-link>

                @auth
                    <x-nav-link :active="request()->routeIs('riwayat-pinjam')" href="{{ route('riwayat-pinjam') }}">Riwayat Peminjaman</x-nav-link>
                @endauth
            </ul>

            @guest
                <div class="d-flex">
                    <a href="{{ route('login') }}" class="btn btn-success fw-bold ms-2">Login</a>
                    <a href="{{ route('register') }}" class="btn btn-outline-success ms-2 fw-bold">Daftar</a>
                </div>
            @endguest


            @auth
                <div class="dropdown">
                    <a href="#" class="d-flex align-items-center text-decoration-none" data-bs-toggle="dropdown"
                        aria-expanded="false">
                        <img src="{{ Auth::user()->avatar ? asset('storage/' . Auth::user()->avatar) : asset('assets/img/user.png') }}"
                            onerror="this.onerror=null; this.src='{{ asset('assets/img/user.png') }}'" class="rounded-circle" width="36px" height="36px"
                            style="object-fit: cover;" alt="Avatar">

                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="{{ route('profil') }}">Profil</a></li>
                        <li>
                            <form action="{{ route('logout') }}" method="put" class="d-inline">
                                @csrf
                                <button type="submit" class="dropdown-item">Logout</button>
                            </form>
                        </li>
                    </ul>
                </div>
            @endauth

        </div>
    </div>
</nav>
