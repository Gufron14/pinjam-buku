@component('mail::message')
# Permohonan Pengembalian Buku

Halo Admin,

Ada permohonan pengembalian buku yang perlu dikonfirmasi.

## Detail Pengembalian:
- **Nama Peminjam:** {{ $loan->user->name }}
- **Email:** {{ $loan->user->email }}
- **No. Telepon:** {{ $loan->user->no_telepon }}
- **Judul Buku:** {{ $loan->book->judul }}
- **Penulis:** {{ $loan->book->penulis }}
- **Tanggal Pinjam:** {{ \Carbon\Carbon::parse($loan->tanggal_pinjam)->format('d F Y') }}
- **Tanggal Pengembalian:** {{ \Carbon\Carbon::parse($loan->tanggal_kembali)->format('d F Y H:i') }}

@php
    $dueDate = \Carbon\Carbon::parse($loan->tanggal_pinjam)->addDays(7);
    $returnDate = \Carbon\Carbon::parse($loan->tanggal_kembali);
    $isLate = $returnDate->gt($dueDate);
    $daysLate = $isLate ? $returnDate->diffInDays($dueDate) : 0;
@endphp

@if($isLate)
@component('mail::panel')
**Peringatan:** Buku dikembalikan terlambat {{ $daysLate }} hari.
Denda yang harus dibayar: Rp{{ number_format($daysLate * 1000, 0, ',', '.') }}
@endcomponent
@else
@component('mail::panel')
**Status:** Buku dikembalikan tepat waktu. Tidak ada denda.
@endcomponent
@endif

@component('mail::button', ['url' => route('peminjaman')])
Konfirmasi Pengembalian
@endcomponent

Silakan login ke sistem untuk mengonfirmasi pengembalian buku ini.

Salam,<br>
Sistem {{ config('app.name') }}
@endcomponent