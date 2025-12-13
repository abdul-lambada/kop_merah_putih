<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Unit Usaha - {{ $unit->name }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            color: #333;
            line-height: 1.4;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #333;
            padding-bottom: 20px;
        }
        .header h1 {
            font-size: 24px;
            margin-bottom: 5px;
            color: #333;
        }
        .header p {
            font-size: 14px;
            color: #666;
            margin: 3px 0;
        }
        .summary-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 20px;
            margin-bottom: 30px;
        }
        .summary-item {
            border: 1px solid #ddd;
            padding: 15px;
            text-align: center;
            background-color: #f9f9f9;
        }
        .summary-item h3 {
            font-size: 18px;
            margin: 10px 0 5px 0;
            color: #333;
        }
        .summary-item p {
            font-size: 12px;
            color: #666;
            margin: 0;
        }
        .section {
            margin-bottom: 30px;
            page-break-inside: avoid;
        }
        .section h2 {
            font-size: 16px;
            border-bottom: 1px solid #333;
            padding-bottom: 5px;
            margin-bottom: 15px;
            color: #333;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            font-size: 12px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f5f5f5;
            font-weight: bold;
        }
        .text-right {
            text-align: right;
        }
        .text-success {
            color: #28a745;
        }
        .text-danger {
            color: #dc3545;
        }
        .text-primary {
            color: #007bff;
        }
        .text-info {
            color: #17a2b8;
        }
        .text-warning {
            color: #ffc107;
        }
        .no-data {
            text-align: center;
            color: #666;
            font-style: italic;
            padding: 20px;
        }
        @page {
            margin: 1.5cm;
            size: A4;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>LAPORAN UNIT USAHA</h1>
        <p><strong>{{ $unit->name }}</strong></p>
        <p>{{ ucfirst($unit->type) }} - {{ $unit->manager_name }}</p>
        <p>{{ date('d F Y') }}</p>
    </div>
    
    <div class="section">
        <h2>RINGKASAN KEUANGAN</h2>
        <div class="summary-grid">
            <div class="summary-item">
                <p>Total Pendapatan</p>
                <h3 class="text-success">Rp {{ number_format($unit->revenue, 0, ',', '.') }}</h3>
            </div>
            <div class="summary-item">
                <p>Total Pengeluaran</p>
                <h3 class="text-danger">Rp {{ number_format($unit->expenses, 0, ',', '.') }}</h3>
            </div>
            <div class="summary-item">
                <p>Laba Bersih</p>
                <h3 class="{{ $unit->profit >= 0 ? 'text-success' : 'text-danger' }}">Rp {{ number_format($unit->profit, 0, ',', '.') }}</h3>
            </div>
            <div class="summary-item">
                <p>Saldo Saat Ini</p>
                <h3 class="text-primary">Rp {{ number_format($unit->current_balance, 0, ',', '.') }}</h3>
            </div>
        </div>
    </div>
    
    <div class="section">
        <h2>TREN 30 HARI TERAKHIR</h2>
        @if(count($dailyTrend) > 0)
            <table>
                <thead>
                    <tr>
                        <th>Tanggal</th>
                        <th class="text-right">Pendapatan</th>
                        <th class="text-right">Pengeluaran</th>
                        <th class="text-right">Net</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach(array_reverse($dailyTrend) as $data)
                    <tr>
                        <td>{{ $data['date'] }}</td>
                        <td class="text-right text-success">Rp {{ number_format($data['income'], 0, ',', '.') }}</td>
                        <td class="text-right text-danger">Rp {{ number_format($data['expense'], 0, ',', '.') }}</td>
                        <td class="text-right {{ ($data['income'] - $data['expense']) >= 0 ? 'text-success' : 'text-danger' }}">
                            Rp {{ number_format($data['income'] - $data['expense'], 0, ',', '.') }}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <div class="no-data">Belum ada data transaksi 30 hari terakhir</div>
        @endif
    </div>
    
    <div class="section">
        <h2>KATEGORI TRANSAKSI TERATAS</h2>
        @if($topCategories->count() > 0)
            <table>
                <thead>
                    <tr>
                        <th>Kategori</th>
                        <th>Tipe</th>
                        <th class="text-right">Jumlah</th>
                        <th class="text-right">Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($topCategories as $category)
                    <tr>
                        <td>{{ $category->category }}</td>
                        <td>{{ $category->type == 'income' ? 'Pendapatan' : 'Pengeluaran' }}</td>
                        <td class="text-right">{{ $category->count }}</td>
                        <td class="text-right {{ $category->type == 'income' ? 'text-success' : 'text-danger' }}">
                            Rp {{ number_format($category->total, 0, ',', '.') }}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <div class="no-data">Belum ada data kategori transaksi</div>
        @endif
    </div>
    
    <div class="section">
        <h2>PERFORMA BULANAN {{ date('Y') }}</h2>
        @if($monthlyData->count() > 0)
            <table>
                <thead>
                    <tr>
                        <th>Bulan</th>
                        <th class="text-right">Pendapatan</th>
                        <th class="text-right">Pengeluaran</th>
                        <th class="text-right">Laba</th>
                        <th class="text-right">Transaksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($monthlyData as $year => $months)
                        @foreach($months as $month => $data)
                            <?php
                            $monthIncome = $data->where('type', 'income')->sum('total');
                            $monthExpense = $data->where('type', 'expense')->sum('total');
                            $monthProfit = $monthIncome - $monthExpense;
                            ?>
                            <tr>
                                <td>{{ $month }} {{ $year }}</td>
                                <td class="text-right text-success">Rp {{ number_format($monthIncome, 0, ',', '.') }}</td>
                                <td class="text-right text-danger">Rp {{ number_format($monthExpense, 0, ',', '.') }}</td>
                                <td class="text-right {{ $monthProfit >= 0 ? 'text-success' : 'text-danger' }}">
                                    Rp {{ number_format($monthProfit, 0, ',', '.') }}
                                </td>
                                <td class="text-right">{{ $data->sum('total') > 0 ? $data->count() : 0 }}</td>
                            </tr>
                        @endforeach
                    @endforeach
                </tbody>
            </table>
        @else
            <div class="no-data">Belum ada data bulanan untuk tahun ini</div>
        @endif
    </div>
    
    <div class="section">
        <h2>INFORMASI UNIT</h2>
        <table>
            <tr>
                <td width="150"><strong>Nama Unit</strong></td>
                <td>{{ $unit->name }}</td>
                <td width="150"><strong>Modal Awal</strong></td>
                <td class="text-primary">Rp {{ number_format($unit->initial_capital, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td><strong>Tipe</strong></td>
                <td>{{ ucfirst($unit->type) }}</td>
                <td><strong>ROI</strong></td>
                <td class="{{ $unit->roi >= 10 ? 'text-success' : ($unit->roi >= 0 ? 'text-warning' : 'text-danger') }}">
                    {{ number_format($unit->roi, 1) }}%
                </td>
            </tr>
            <tr>
                <td><strong>Status</strong></td>
                <td>{{ $unit->status == 'active' ? 'Aktif' : ($unit->status == 'inactive' ? 'Tidak Aktif' : 'Maintenance') }}</td>
                <td><strong>Total Transaksi</strong></td>
                <td>{{ $unit->transactions()->count() }} transaksi</td>
            </tr>
            <tr>
                <td><strong>Manajer</strong></td>
                <td>{{ $unit->manager_name }}</td>
                <td><strong>Dibuat</strong></td>
                <td>{{ $unit->created_at->format('d M Y') }}</td>
            </tr>
            @if($unit->address)
            <tr>
                <td><strong>Alamat</strong></td>
                <td colspan="3">{{ $unit->address }}</td>
            </tr>
            @endif
            @if($unit->phone)
            <tr>
                <td><strong>Telepon</strong></td>
                <td colspan="3">{{ $unit->phone }}</td>
            </tr>
            @endif
        </table>
        
        @if($unit->description)
        <div style="margin-top: 15px;">
            <strong>Deskripsi:</strong>
            <p style="margin: 5px 0;">{{ $unit->description }}</p>
        </div>
        @endif
    </div>
    
    <div style="text-align: center; margin-top: 40px; color: #666; font-size: 12px;">
        <p>Laporan ini dibuat secara otomatis pada {{ date('d F Y H:i') }}</p>
        <p>Koperasi Merah Putih - Sistem Manajemen Unit Usaha</p>
    </div>
</body>
</html>
