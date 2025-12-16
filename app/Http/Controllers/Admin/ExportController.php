<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Exports\MembersExport;
use App\Exports\FinancialReportExport;
use App\Models\Member;
use App\Models\SavingsLoan;
use App\Models\BusinessUnit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ExportController extends Controller
{
    public function members(Request $request)
    {
        $status = $request->get('status');
        $businessSector = $request->get('business_sector');
        
        $export = new MembersExport($status, $businessSector);
        return $export->download();
    }

    public function financialReport(Request $request)
    {
        $startDate = $request->get('start_date');
        $endDate = $request->get('end_date');
        $type = $request->get('type');
        
        $export = new FinancialReportExport($startDate, $endDate, $type);
        return $export->download();
    }

    public function summaryReport()
    {
        $totalMembers = Member::count();
        $activeMembers = Member::where('status', 'active')->count();
        $pendingMembers = Member::where('status', 'pending')->count();
        
        $totalSavings = SavingsLoan::where('type', 'savings')->sum('amount');
        $totalLoans = SavingsLoan::where('type', 'loan')->sum('amount');
        
        $businessUnits = BusinessUnit::count();
        
        $membersBySector = Member::select('business_sector', DB::raw('count(*) as count'))
                                ->groupBy('business_sector')
                                ->get();
        
        $monthlyTransactions = SavingsLoan::select(
                DB::raw('MONTH(transaction_date) as month'),
                DB::raw('YEAR(transaction_date) as year'),
                DB::raw('SUM(CASE WHEN type = "savings" THEN amount ELSE 0 END) as total_savings'),
                DB::raw('SUM(CASE WHEN type = "loan" THEN amount ELSE 0 END) as total_loans')
            )
            ->whereYear('transaction_date', now()->year)
            ->groupBy(DB::raw('YEAR(transaction_date)'), DB::raw('MONTH(transaction_date)'))
            ->orderBy('year')
            ->orderBy('month')
            ->get();
        
        $filename = 'laporan_ringkasan_' . date('Y-m-d_H-i-s') . '.csv';
        
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        
        $output = fopen('php://output', 'w');
        
        // Summary Header
        fputcsv($output, ['Ringkasan Koperasi Merah Putih']);
        fputcsv($output, []);
        
        // Total Statistics
        fputcsv($output, ['Statistik Total']);
        fputcsv($output, ['Total Anggota', $totalMembers]);
        fputcsv($output, ['Anggota Aktif', $activeMembers]);
        fputcsv($output, ['Anggota Pending', $pendingMembers]);
        fputcsv($output, ['Total Simpanan', 'Rp ' . number_format($totalSavings, 0, ',', '.')]);
        fputcsv($output, ['Total Pinjaman', 'Rp ' . number_format($totalLoans, 0, ',', '.')]);
        fputcsv($output, ['Unit Usaha', $businessUnits]);
        fputcsv($output, []);
        
        // Members by Sector
        fputcsv($output, ['Anggota per Sektor']);
        foreach ($membersBySector as $sector) {
            fputcsv($output, [ucfirst($sector->business_sector), $sector->count]);
        }
        fputcsv($output, []);
        
        // Monthly Transactions
        fputcsv($output, ['Transaksi Bulanan']);
        fputcsv($output, ['Bulan', 'Tahun', 'Total Simpanan', 'Total Pinjaman']);
        foreach ($monthlyTransactions as $transaction) {
            fputcsv($output, [
                $transaction->month,
                $transaction->year,
                'Rp ' . number_format($transaction->total_savings, 0, ',', '.'),
                'Rp ' . number_format($transaction->total_loans, 0, ',', '.')
            ]);
        }
        
        fclose($output);
        exit;
    }
}
