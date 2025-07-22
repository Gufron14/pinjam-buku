<!-- ========== Left Sidebar Start ========== -->
<div class="vertical-menu">

    <!-- LOGO -->
    <div class="navbar-brand-box">
        <a href="{{ route('dashboard') }}" class="logo logo-dark">
            <span class="logo-sm">
                <img src="{{ URL::asset('/assets/img/read.png') }}" alt="" height="22">
            </span>
            <span class="logo-lg">
                <img src="{{ URL::asset('/assets/img/read.png') }}" alt="" height="20">
                <span class="ms-2 fw-bold">Jambu TaBaBa</span>
            </span>
        </a>

        <a href="{{ url('index') }}" class="logo logo-light">
            <span class="logo-sm">
                <img src="{{ URL::asset('/assets/images/logo-sm.png') }}" alt="" height="22">
            </span>
            <span class="logo-lg">
                <img src="{{ URL::asset('/assets/images/logo-light.png') }}" alt="" height="20">
            </span>
        </a>
    </div>

    <button type="button" class="btn btn-sm px-3 font-size-16 header-item waves-effect vertical-menu-btn">
        <i class="fa fa-fw fa-bars"></i>
    </button>

    <div data-simplebar class="sidebar-menu-scroll">

        <!--- Sidemenu -->
        <div id="sidebar-menu">
            <!-- Left Menu Start -->
            <ul class="metismenu list-unstyled" id="side-menu">
                <li class="menu-title">@lang('translation.Menu')</li>

                <li>
                    <a href="{{ route('dashboard') }}" class="waves-effect">
                        <i class="uil-home-alt"></i>
                        <span>@lang('translation.Dashboard')</span>
                    </a>
                </li>

                {{-- Peminjaman --}}
                <li>
                    @php
                        $peminjaman = \App\Models\LoanHistory::where('status', 'pending')->count();
                    @endphp
                        <a href="{{ route('peminjaman') }}" class="waves-effect">
                            <i class="uil-fast-mail"></i>
                            @if ($peminjaman > 0)                        
                                <span class="badge rounded-pill bg-primary float-end">{{ $peminjaman }}</span>
                            @endif
                            <span>Peminjaman</span>
                        </a>
                </li>

                {{-- Pengembalian --}}
                {{-- <li>
                    <a href="{{ route('pengembalian') }}" class="waves-effect">
                        <i class="uil-backward"></i><span class="badge rounded-pill bg-primary float-end">01</span>
                        <span>Pengembalian</span>
                    </a>
                </li> --}}

                {{-- <li class="menu-title">Kelola</li> --}}

                {{-- Buku --}}
                <li>
                    <a href="{{ route('kelola-buku') }}" class="waves-effect">
                        <i class="uil-books"></i>
                        <span>Kelola Buku</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('laporan') }}" class="waves-effect">
                        <i class="uil-document-layout-left"></i>
                        <span>Laporan</span>
                    </a>
                </li>

                {{-- <li>
                    <a href="{{ route('kelola-denda') }}" class="waves-effect">
                        <i class="uil-user-times"></i>
                        <span>Kelola Denda</span>
                    </a>
                </li> --}}
                <li>
                    <a href="{{ route('kelola-member') }}" class="waves-effect">
                        <i class="uil-users-alt"></i>
                        <span>Daftar Member</span>
                    </a>
                </li>
            </ul>
        </div>
        <!-- Sidebar -->
    </div>
</div>
<!-- Left Sidebar End -->
