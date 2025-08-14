@component('mail::message')
# Peminjaman Buku Ditolak

Halo **{{ $loan->user->name }}**,

Mohon maaf, permohonan peminjaman buku Anda telah ditolak oleh admin.

## Detail Peminjaman:
- **Judul Buku:** {{ $loan->book->judul }}
- **Penulis:** {{ $loan->book->penulis }}
- **Tanggal Permohonan:** {{ \Carbon\Carbon::parse($loan->created_at)->format('d F Y H:i') }}

@component('mail::panel')
**Kemungkinan Alasan Penolakan:**
- Stok buku tidak tersedia
- Buku sedang dalam perbaikan
- Anda memiliki tunggakan denda
- Alasan administratif lainnya

Silakan hubungi admin untuk informasi lebih lanjut atau coba pinjam buku lain yang tersedia.
@endcomponent

@component('mail::button', ['url' => route('daftar-buku')])
Lihat Buku Lainnya
@endcomponent

Terima kasih atas pengertian Anda.

Salam,<br>
{{ config('app.name') }}
@endcomponent