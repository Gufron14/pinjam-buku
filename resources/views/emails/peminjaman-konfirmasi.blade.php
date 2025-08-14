@component('mail::message')
# Peminjaman Buku Dikonfirmasi

Halo **{{ $loan->user->name }}**,

Selamat! Permohonan peminjaman buku Anda telah dikonfirmasi oleh admin.

## Detail Peminjaman:
- **Judul Buku:** {{ $loan->book->judul }}
- **Penulis:** {{ $loan->book->penulis }}
- **Tanggal Pinjam:** {{ \Carbon\Carbon::parse($loan->tanggal_pinjam)->format('d F Y') }}
- **Batas Pengembalian:** {{ \Carbon\Carbon::parse($loan->tanggal_pinjam)->addDays(7)->format('d F Y') }}

@component('mail::panel')
**Penting:** Silakan datang ke Taman Baca Balarea untuk mengambil buku Anda. Jangan lupa untuk mengembalikan buku tepat waktu untuk menghindari denda.
@endcomponent

@component('mail::button', ['url' => route('daftar-buku')])
Lihat Katalog Buku
@endcomponent

Terima kasih telah menggunakan layanan Taman Baca Balarea.

Salam,<br>
{{ config('app.name') }}
@endcomponent