<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-4Q6Gf2aSP4eDXB8Miphtr37CMZZQ5oXLH2yaXMJ2w8e2ZtHTl7GptT4jmndRuHDT" crossorigin="anonymous">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&display=swap"
        rel="stylesheet">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">

    <title>{{ $title ?? 'Page Title' }}</title>
</head>

<body>
    @include('components.navbar')

    <div class="min-h-screen">
        {{ $slot }}
    </div>

    <a href="https://wa.me/6283829253885?text=Hallo%20Taman%20Baca%20Balarea" target="_blank" class="whatsapp-button">
        <i class="bi bi-whatsapp"></i> Hubungi Kami
    </a>

    <footer class="bg-body-tertiary text-center text-lg-start mt-5">
        <div class="d-flex justify-content-between align-items-middle mx-5 py-3">
            <div>
                <h5 class="fw-bold">Taman Baca Balarea</h5>
                Desa Tanjungwangi, Kecamatan Cicalengka, Kabupaten Bandung, Jawa Barat
            </div>
            <div class="align-self-center">
                <div class="fs-5">
                    <a href="https://wa.me/62895372513547"><i class="bi bi-telephone-fill"></i></a>
                    <a
                        href="https://www.instagram.com/hypecustom.project?utm_source=ig_web_button_share_sheet&igsh=ZDNlZDc0MzIxNw=="><i
                            class="bi bi-instagram"></i></a>
                </div>
                © 2025 Taman Baca Balarea
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-j1CDi7MgGQ12Z7Qab0qlWQ/Qqz24Gc6BM0thvEMVjHnfYGF0rmFCozFSxQBxwHKO" crossorigin="anonymous">
    </script>
    @stack('scripts')
</body>

</html>
