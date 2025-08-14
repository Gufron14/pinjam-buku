@component('mail::message')
# Permohonan Peminjaman Buku Baru

Halo Admin,

Ada permohonan peminjaman buku baru yang perlu dikonfirmasi.

## Detail Peminjaman:
- **Nama Peminjam:** {{ $loan->user->name }}
- **Email:** {{ $loan->user->email }}
- **No. Telepon:** {{ $loan->user->no_telepon }}
- **Judul Buku:** {{ $loan->book->judul }}
- **Penulis:** {{ $loan->book->penulis }}
- **Stok Tersedia:** {{ $loan->book->stok }}
- **Tanggal Permohonan:** {{ \Carbon\Carbon::parse($loan->created_at)->format('d F Y H:i') }}

@component('mail::panel')
**Status:** Menunggu konfirmasi admin
@endcomponent

@component('mail::button', ['url' => route('peminjaman')])
Kelola Peminjaman
@endcomponent

Silakan login ke sistem untuk mengonfirmasi atau menolak permohonan ini.

Salam,<br>
Sistem {{ config('app.name') }}
@endcomponent