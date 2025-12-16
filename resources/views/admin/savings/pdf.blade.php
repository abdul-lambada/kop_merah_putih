<!DOCTYPE html>
<html>
<head>
    <title>Laporan Data Simpanan - Koperasi Merah Putih</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 10px;
            margin: 15px;
            line-height: 1.2;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #333;
            padding-bottom: 15px;
        }
        .header h1 {
            margin: 0;
            font-size: 16px;
            color: #333;
        }
        .header p {
            margin: 3px 0;
            color: #666;
            font-size: 9px;
        }
        .info {
            margin-bottom: 15px;
            font-size: 9px;
            color: #666;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 5px;
            text-align: left;
        }
        th {
            background-color: #f5f5f5;
            font-weight: bold;
            font-size: 9px;
        }
        td {
            font-size: 9px;
        }
        .text-center {
            text-align: center;
        }
        .text-right {
            text-align: right;
        }
        .badge {
            padding: 1px 4px;
            border-radius: 2px;
            font-size: 8px;
            font-weight: bold;
        }
        .badge-completed {
            background-color: #d4edda;
            color: #155724;
        }
        .badge-pending {
            background-color: #fff3cd;
            color: #856404;
        }
        .badge-approved {
            background-color: #cce5ff;
            color: #004085;
        }
        .badge-rejected {
            background-color: #f8d7da;
            color: #721c24;
        }
        .footer {
            margin-top: 20px;
            text-align: center;
            font-size: 8px;
            color: #666;
            border-top: 1px solid #ddd;
            padding-top: 8px;
        }
        .page-break {
            page-break-before: always;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>LAPORAN DATA SIMPANAN</h1>
        <p>KOPERASI MERAH PUTIH</p>
        <p>{{ date('d F Y') }}</p>
    </div>

    <div class="info">
        <strong>Total Transaksi:</strong> {{ $savings->count() }} transaksi
        <br><strong>Total Nominal:</strong> Rp {{ number_format($savings->sum('amount'), 0, ',', '.') }}
        @if(request('status'))
            <br><strong>Filter Status:</strong> {{ request('status') }}
        @endif
        @if(request('search'))
            <br><strong>Pencarian:</strong> {{ request('search') }}
        @endif
        @if(request('date_from') || request('date_to'))
            <br><strong>Periode:</strong>
            {{ request('date_from') ? \Carbon\Carbon::parse(request('date_from'))->format('d/m/Y') : '-' }}
            s/d
            {{ request('date_to') ? \Carbon\Carbon::parse(request('date_to'))->format('d/m/Y') : '-' }}
        @endif
    </div>

    <table>
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="15%">No. Transaksi</th>
                <th width="25%">Anggota</th>
                <th width="15%">Tanggal</th>
                <th width="15%">Jumlah</th>
                <th width="10%">Status</th>
                <th width="15%">Keterangan</th>
            </tr>
        </thead>
        <tbody>
            @forelse($savings as $index => $saving)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>{{ $saving->transaction_number }}</td>
                    <td>
                        {{ $saving->member->full_name ?? '-' }}<br>
                        <small>{{ $saving->member->member_number ?? '' }}</small>
                    </td>
                    <td class="text-center">{{ $saving->created_at ? $saving->created_at->format('d/m/Y') : '-' }}</td>
                    <td class="text-right">Rp {{ number_format($saving->amount, 0, ',', '.') }}</td>
                    <td class="text-center">
                        @php($status = $saving->status ?? 'pending')
                        <span class="badge badge-{{ $status }}">
                            {{ strtoupper($status) }}
                        </span>
                    </td>
                    <td>{{ $saving->notes ?? '-' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center">Tidak ada data simpanan</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        <p>Laporan ini dicetak pada: {{ date('d F Y H:i:s') }}</p>
        <p>© Koperasi Merah Putih - Sistem Informasi Koperasi</p>
    </div>
</body>
</html>
