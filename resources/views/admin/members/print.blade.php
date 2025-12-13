<!DOCTYPE html>
<html>
<head>
    <title>Laporan Data Anggota - Koperasi Merah Putih</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            margin: 20px;
            line-height: 1.4;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #333;
            padding-bottom: 20px;
        }
        .header h1 {
            margin: 0;
            font-size: 18px;
            color: #333;
        }
        .header p {
            margin: 5px 0;
            color: #666;
        }
        .info {
            margin-bottom: 20px;
            font-size: 11px;
            color: #666;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f5f5f5;
            font-weight: bold;
            font-size: 11px;
        }
        td {
            font-size: 11px;
        }
        .text-center {
            text-align: center;
        }
        .badge {
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 10px;
            font-weight: bold;
        }
        .badge-active {
            background-color: #d4edda;
            color: #155724;
        }
        .badge-pending {
            background-color: #fff3cd;
            color: #856404;
        }
        .badge-inactive {
            background-color: #f8d7da;
            color: #721c24;
        }
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 10px;
            color: #666;
            border-top: 1px solid #ddd;
            padding-top: 10px;
        }
        @media print {
            body { margin: 10px; }
            .no-print { display: none; }
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>LAPORAN DATA ANGGOTA</h1>
        <p>KOPERASI MERAH PUTIH</p>
        <p>{{ date('d F Y') }}</p>
    </div>

    <div class="info">
        <strong>Total Anggota:</strong> {{ $members->count() }} orang
        @if(request('status'))
            <br><strong>Filter Status:</strong> {{ request('status') }}
        @endif
        @if(request('search'))
            <br><strong>Pencarian:</strong> {{ request('search') }}
        @endif
    </div>

    <table>
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="12%">No. Anggota</th>
                <th width="20%">Nama Lengkap</th>
                <th width="15%">Email</th>
                <th width="10%">No. HP</th>
                <th width="10%">Tanggal Daftar</th>
                <th width="8%">Status</th>
                <th width="20%">Alamat</th>
            </tr>
        </thead>
        <tbody>
            @forelse($members as $index => $member)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>{{ $member->member_number }}</td>
                    <td>{{ $member->full_name }}</td>
                    <td>{{ $member->email }}</td>
                    <td>{{ $member->phone_number ?: '-' }}</td>
                    <td class="text-center">{{ $member->join_date ? \Carbon\Carbon::parse($member->join_date)->format('d/m/Y') : '-' }}</td>
                    <td class="text-center">
                        <span class="badge badge-{{ $member->status }}">
                            {{ strtoupper($member->status) }}
                        </span>
                    </td>
                    <td>{{ $member->address ?: '-' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" class="text-center">Tidak ada data anggota</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        <p>Laporan ini dicetak pada: {{ date('d F Y H:i:s') }}</p>
        <p>© Koperasi Merah Putih - Sistem Informasi Koperasi</p>
    </div>

    <script>
        window.onload = function() {
            window.print();
        }
    </script>
</body>
</html>
