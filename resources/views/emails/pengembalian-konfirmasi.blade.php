@component('mail::message')
# Pengembalian Buku Dikonfirmasi

Halo **{{ $loan->user->name }}**,

Terima kasih! Pengembalian buku Anda telah dikonfirmasi oleh admin.

## Detail Pengembalian:
- **Judul Buku:** {{ $loan->book->judul }}
- **Penulis:** {{ $loan->book->penulis }}
- **Tanggal Pinjam:** {{ \Carbon\Carbon::parse($loan->tanggal_pinjam)->format('d F Y') }}
- **Tanggal Kembali:** {{ \Carbon\Carbon::parse($loan->tanggal_kembali)->format('d F Y') }}

@if($loan->denda > 0)
@component('mail::panel')
**Denda:** Rp{{ number_format($loan->denda, 0, ',', '.') }}
@if($loan->denda_dibayar)
Status: **Sudah Dibayar**
@else
Status: **Belum Dibayar**
@endif
@endcomponent
@else
@component('mail::panel')
**Selamat!** Tidak ada denda untuk peminjaman ini karena Anda mengembalikan buku tepat waktu.
@endcomponent
@endif

@component('mail::button', ['url' => route('daftar-buku')])
Pinjam Buku Lainnya
@endcomponent

Terima kasih telah menggunakan layanan Taman Baca Balarea dengan baik.

Salam,<br>
{{ config('app.name') }}
@endcomponent