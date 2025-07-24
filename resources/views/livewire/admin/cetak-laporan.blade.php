<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan {{ ucfirst($type) }} - Taman Baca Balarea</title>
    <style>
        @media print {
            .no-print {
                display: none !important;
            }

            body {
                -webkit-print-color-adjust: exact;
            }
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 20px;
            background: white;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #007bff;
            padding-bottom: 20px;
        }

        .header h1 {
            color: #007bff;
            margin: 0;
            font-size: 24px;
        }

        .header h2 {
            color: #666;
            margin: 5px 0;
            font-size: 18px;
        }

        .info-section {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            border-left: 4px solid #007bff;
        }

        .info-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 5px;
        }

        .info-row:last-child {
            margin-bottom: 0;
        }

        .info-label {
            font-weight: bold;
            color: #333;
        }

        .info-value {
            color: #666;
        }

        .statistics {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
            margin-bottom: 30px;
        }

        .stat-card {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 8px;
            text-align: center;
            border: 1px solid #dee2e6;
        }

        .stat-title {
            font-size: 12px;
            color: #666;
            text-transform: uppercase;
            margin-bottom: 5px;
        }

        .stat-value {
            font-size: 24px;
            font-weight: bold;
            color: #007bff;
        }

        .table-container {
            background: white;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 12px;
        }

        th {
            background: #007bff;
            color: white;
            padding: 12px 8px;
            text-align: left;
            font-weight: 600;
        }

        td {
            padding: 10px 8px;
            border-bottom: 1px solid #dee2e6;
        }

        tr:nth-child(even) {
            background: #f8f9fa;
        }

        .badge {
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 10px;
            font-weight: 500;
        }

        .badge-success {
            background: #d4edda;
            color: #155724;
        }

        .badge-warning {
            background: #fff3cd;
            color: #856404;
        }

        .badge-danger {
            background: #f8d7da;
            color: #721c24;
        }

        .badge-info {
            background: #d1ecf1;
            color: #0c5460;
        }

        .badge-primary {
            background: #cce5ff;
            color: #004085;
        }

        .print-btn {
            background: #007bff;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
            margin-bottom: 20px;
        }

        .print-btn:hover {
            background: #0056b3;
        }

        .footer {
            margin-top: 30px;
            text-align: center;
            color: #666;
            font-size: 12px;
            border-top: 1px solid #dee2e6;
            padding-top: 15px;
        }

        .no-data {
            text-align: center;
            padding: 40px;
            color: #666;
        }

        .currency {
            font-weight: bold;
            color: #28a745;
        }

        .text-danger {
            color: #dc3545;
        }

        .text-success {
            color: #28a745;
        }

        .text-warning {
            color: #ffc107;
        }

        .text-info {
            color: #17a2b8;
        }
    </style>
</head>

<body>
    <button class="print-btn no-print" onclick="window.print()">🖨️ Cetak Laporan</button>

    <div class="header">
        <h1>TAMAN BACA BALAREA</h1>
        <h2>LAPORAN {{ strtoupper($type) }}</h2>
    </div>

    <div class="info-section">
        <div class="info-row">
            <span class="info-label">Jenis Laporan:</span>
            <span class="info-value">{{ ucfirst($type) }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">Periode:</span>
            <span class="info-value">{{ \Carbon\Carbon::parse($startDate)->format('d/m/Y') }} -
                {{ \Carbon\Carbon::parse($endDate)->format('d/m/Y') }}</span>
        </div>
        @if ($status)
            <div class="info-row">
                <span class="info-label">Status Filter:</span>
                <span class="info-value">{{ ucfirst($status) }}</span>
            </div>
        @endif
        <div class="info-row">
            <span class="info-label">Tanggal Cetak:</span>
            <span class="info-value">{{ \Carbon\Carbon::now()->format('d/m/Y H:i') }}</span>
        </div>
    </div>

    {{-- Statistik --}}
    <div class="statistics">
        <div class="stat-card">
            <div class="stat-title">Total Record</div>
            <div class="stat-value">{{ $data->count() }}</div>
        </div>
        @if ($type == 'peminjaman')
            <div class="stat-card">
                <div class="stat-title">Dipinjam</div>
                <div class="stat-value">{{ $data->where('status', 'dipinjam')->count() }}</div>
            </div>
            <div class="stat-card">
                <div class="stat-title">Dikembalikan</div>
                <div class="stat-value">{{ $data->where('status', 'dikembalikan')->count() }}</div>
            </div>
            <div class="stat-card">
                <div class="stat-title">Terlambat</div>
                <div class="stat-value">{{ $data->where('status', 'terlambat')->count() }}</div>
            </div>
        @endif
        @if ($type == 'denda')
            <div class="stat-card">
                <div class="stat-title">Total Denda</div>
                <div class="stat-value currency">Rp {{ number_format($data->sum('denda'), 0, ',', '.') }}</div>
            </div>
            <div class="stat-card">
                <div class="stat-title">Sudah Bayar</div>
                <div class="stat-value currency">Rp
                    {{ number_format($data->where('denda_dibayar', true)->sum('denda'), 0, ',', '.') }}</div>
            </div>
        @endif
    </div>

    <div class="table-container">
        @if ($data->count() > 0)
            <table>
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Peminjam</th>
                        <th>Judul Buku</th>
                        <th>Tanggal Pinjam</th>
                        @if ($type == 'peminjaman')
                            <th>Tanggal Kembali</th>
                            <th>Status</th>
                            <th>Denda</th>
                        @elseif($type == 'pengembalian')
                            <th>Tanggal Kembali</th>
                            <th>Lama Pinjam</th>
                            <th>Kondisi</th>
                        @elseif($type == 'denda')
                            {{-- <th>Keterlambatan</th> --}}
                            <th>Jumlah Denda</th>
                            <th>Status Bayar</th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @foreach ($data as $index => $item)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>
                                <div>
                                    <strong>{{ $item->user->name ?? 'N/A' }}</strong><br>
                                    <small>{{ $item->user->email ?? 'N/A' }}</small>
                                </div>
                            </td>
                            <td>
                                <div>
                                    <strong>{{ $item->book->judul ?? 'Buku tidak ditemukan' }}</strong><br>
                                    <small>{{ $item->book->penulis ?? '' }}</small>
                                </div>
                            </td>
                            <td>{{ \Carbon\Carbon::parse($item->tanggal_pinjam)->format('d/m/Y H:i') }}</td>

                            @if ($type == 'peminjaman')
                                <td>
                                    @if ($item->tanggal_kembali)
                                        {{ \Carbon\Carbon::parse($item->tanggal_kembali)->format('d/m/Y H:i') }}
                                    @else
                                        <span style="color: #666;">Belum dikembalikan</span>
                                    @endif
                                </td>
                                <td>
                                    @if ($item->status == 'pending')
                                        <span class="badge badge-warning">Pending</span>
                                    @elseif($item->status == 'dipinjam')
                                        <span class="badge badge-info">Dipinjam</span>
                                    @elseif($item->status == 'dikembalikan')
                                        <span class="badge badge-success">Dikembalikan</span>
                                    @elseif($item->status == 'ditolak')
                                        <span class="badge badge-danger">Ditolak</span>
                                    @elseif($item->status == 'selesai')
                                        <span class="badge badge-success">Selesai</span>
                                    @elseif($item->status == 'terlambat')
                                        <span class="badge badge-danger">Terlambat</span>
                                    @endif
                                </td>
                                <td>
                                    @if ($item->denda > 0)
                                        <div>
                                            <span class="text-danger currency">Rp
                                                {{ number_format($item->denda, 0, ',', '.') }}</span>
                                            @if ($item->denda_dibayar)
                                                <br><span class="badge badge-success">Lunas</span>
                                            @else
                                                <br><span class="badge badge-danger">Belum Bayar</span>
                                            @endif
                                        </div>
                                    @else
                                        <span style="color: #666;">-</span>
                                    @endif
                                </td>
                            @elseif($type == 'pengembalian')
                                <td>{{ \Carbon\Carbon::parse($item->tanggal_kembali)->format('d/m/Y H:i') }}</td>
                                <td>
                                    @php
                                        $lamaPinjam = \Carbon\Carbon::parse($item->tanggal_pinjam)->diffInDays(
                                            \Carbon\Carbon::parse($item->tanggal_kembali),
                                        );
                                    @endphp
                                    {{ $lamaPinjam }} hari
                                </td>
                                <td>
                                    @if ($item->denda > 0)
                                        <span class="badge badge-warning">Terlambat</span>
                                    @else
                                        <span class="badge badge-success">Tepat Waktu</span>
                                    @endif
                                </td>
                            @elseif($type == 'denda')
                                    @php
                                        $fineInfo = $item->getFineInfo();
                                    @endphp
                                {{-- <td>
                                    <div>
                                        <span class="text-danger"><strong>{{ $fineInfo['seconds_overdue'] }}
                                                detik</strong></span><br>
                                        <small>Batas: {{ $fineInfo['due_date'] }}</small>
                                    </div>
                                </td> --}}
                                <td>
                                    <div>
                                        <span class="text-danger currency">Rp
                                            {{ number_format($item->denda, 0, ',', '.') }}</span><br>
                                        <small>@ Rp
                                            {{ number_format($fineInfo['fine_per_book'], 0, ',', '.') }}/detik</small>
                                    </div>
                                </td>
                                <td>
                                    @if ($item->denda_dibayar)
                                        <span class="badge badge-success">Lunas</span>
                                    @else
                                        <span class="badge badge-danger">Belum Bayar</span>
                                    @endif
                                </td>
                            @endif
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <div class="no-data">
                <h3>Tidak ada data untuk ditampilkan</h3>
                <p>Tidak ada data {{ $type }} pada periode yang dipilih.</p>
            </div>
        @endif
    </div>

    <div class="footer">
        <p>Laporan ini dicetak pada {{ \Carbon\Carbon::now()->format('d/m/Y H:i') }} WIB</p>
        <p>© {{ \Carbon\Carbon::now()->year }} Taman Baca Balarea - Sistem Peminjaman Buku</p>
    </div>

    <script>
        // Auto print when page loads (optional)
        // window.onload = function() { window.print(); }

        // Print function
        function printReport() {
            window.print();
        }
    </script>
</body>

</html>
