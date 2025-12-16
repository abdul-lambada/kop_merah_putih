<?php

namespace App\Exports;

use App\Models\SavingsLoan;

class FinancialReportExport
{
    protected $startDate;
    protected $endDate;
    protected $type;

    public function __construct($startDate = null, $endDate = null, $type = null)
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
        $this->type = $type;
    }

    public function download()
    {
        $query = SavingsLoan::with('member');

        if ($this->startDate) {
            $query->whereDate('transaction_date', '>=', $this->startDate);
        }

        if ($this->endDate) {
            $query->whereDate('transaction_date', '<=', $this->endDate);
        }

        if ($this->type) {
            $query->where('type', $this->type);
        }

        $transactions = $query->orderBy('transaction_date', 'desc')->get();

        $filename = 'laporan_keuangan_' . date('Y-m-d_H-i-s') . '.csv';
        
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        
        $output = fopen('php://output', 'w');
        
        // CSV Header
        fputcsv($output, [
            'ID', 'No. Anggota', 'Nama Anggota', 'Tipe Transaksi', 'Jumlah',
            'Deskripsi', 'Tanggal Transaksi', 'Status', 'Jatuh Tempo', 
            'Bunga (%)', 'Total Pembayaran'
        ]);
        
        // CSV Data
        foreach ($transactions as $transaction) {
            fputcsv($output, [
                $transaction->id,
                $transaction->member->member_number ?? '-',
                $transaction->member->full_name ?? '-',
                ucfirst($transaction->type),
                'Rp ' . number_format($transaction->amount, 0, ',', '.'),
                $transaction->description ?? '-',
                $transaction->transaction_date->format('d/m/Y'),
                ucfirst($transaction->status),
                $transaction->due_date ? $transaction->due_date->format('d/m/Y') : '-',
                $transaction->interest_rate ? $transaction->interest_rate . '%' : '-',
                $transaction->total_payment ? 'Rp ' . number_format($transaction->total_payment, 0, ',', '.') : '-'
            ]);
        }
        
        fclose($output);
        exit;
    }
}
