@php
use App\Models\LoanHistory;
use App\Models\User;
use App\Models\Book;

$loan = $this->loan;
$user = $loan->user;
$book = $loan->book;
@endphp

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Receipt Peminjaman</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @media print {
            body {
                background: white !important;
                margin: 0;
                padding: 0;
            }
            .no-print {
                display: none;
            }
            .receipt-container {
                box-shadow: none !important;
                border: none !important;
                margin: 0 !important;
                padding: 0 !important;
                width: 100% !important;
            }
        }
    </style>
</head>
<body class="bg-gray-100">
    <div class="max-w-3xl mx-auto my-8 bg-white rounded-lg shadow-lg overflow-hidden receipt-container">
        <div class="bg-blue-600 text-white p-6">
            <h1 class="text-2xl font-bold">Receipt Peminjaman Buku</h1>
            <p class="text-blue-100">Taman Baca</p>
        </div>

        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div>
                    <h2 class="text-lg font-semibold mb-4 text-gray-800 border-b pb-2">Informasi Peminjam</h2>
                    <div class="space-y-2">
                        <p><span class="font-medium">Nama:</span> {{ $user->name }}</p>
                        <p><span class="font-medium">Email:</span> {{ $user->email }}</p>
                        <p><span class="font-medium">No. Telepon:</span> {{ $user->no_telepon }}</p>
                        <p><span class="font-medium">Alamat:</span> {{ $user->alamat }}</p>
                    </div>
                </div>

                <div>
                    <h2 class="text-lg font-semibold mb-4 text-gray-800 border-b pb-2">Informasi Peminjaman</h2>
                    <div class="space-y-2">
                        <p><span class="font-medium">ID Peminjaman:</span> {{ $loan->id_pinjaman }}</p>
                        <p><span class="font-medium">Tanggal Pinjam:</span> {{ $loan->tanggal_pinjam }}</p>
                        <p><span class="font-medium">Tanggal Kembali:</span> {{ $loan->tanggal_kembali ?? 'Belum dikembalikan' }}</p>
                        <p><span class="font-medium">Status:</span> {{ ucfirst($loan->status) }}</p>
                    </div>
                </div>
            </div>

            <div class="mt-8">
                <h2 class="text-lg font-semibold mb-4 text-gray-800 border-b pb-2">Informasi Buku</h2>
                <div class="space-y-2">
                    <p><span class="font-medium">Judul:</span> {{ $book->judul }}</p>
                    <p><span class="font-medium">Penulis:</span> {{ $book->penulis }}</p>
                    <p><span class="font-medium">Tahun Terbit:</span> {{ $book->tahun_terbit }}</p>
                </div>
            </div>

            <div class="mt-8 flex justify-end no-print">
                <button 
                    onclick="window.print()" 
                    class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition-colors"
                >
                    Cetak Receipt
                </button>
            </div>
        </div>

        <div class="bg-gray-50 p-4 text-center text-sm text-gray-500">
            Terima kasih telah meminjam di Taman Baca kami.
        </div>
    </div>
</body>
</html>