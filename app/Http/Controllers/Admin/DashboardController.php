<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Member;
use App\Models\SavingsLoan;
use App\Models\BusinessUnit;
use App\Models\Transaction;
use App\Models\FinancialReport;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Get current date ranges
        $today = Carbon::today();
        $thisMonth = Carbon::now()->startOfMonth();
        $lastMonth = Carbon::now()->subMonth()->startOfMonth();
        $lastMonthEnd = Carbon::now()->subMonth()->endOfMonth();

        // Calculate savings growth
        $lastMonthSavings = SavingsLoan::where('type', 'savings')
            ->where('status', 'completed')
            ->whereMonth('created_at', Carbon::now()->subMonth()->month)
            ->whereYear('created_at', Carbon::now()->subMonth()->year)
            ->sum('amount');
        
        $thisMonthSavings = SavingsLoan::where('type', 'savings')
            ->where('status', 'completed')
            ->whereMonth('created_at', Carbon::now()->month)
            ->whereYear('created_at', Carbon::now()->year)
            ->sum('amount');

        // Statistics
        $stats = [
            'total_members' => Member::where('status', 'active')->count(),
            'new_members_this_month' => Member::whereMonth('join_date', Carbon::now()->month)
                ->whereYear('join_date', Carbon::now()->year)->count(),
            'total_savings' => SavingsLoan::where('type', 'savings')
                ->where('status', 'completed')->sum('amount'),
            'savings_growth' => $thisMonthSavings - $lastMonthSavings,
            'total_loans' => SavingsLoan::where('type', 'loan')
                ->whereIn('status', ['active', 'completed'])->sum('amount'),
            'active_loan_portfolio' => SavingsLoan::where('type', 'loan')
                ->where('status', 'active')->sum('amount'),
            'overdue_loans' => SavingsLoan::where('type', 'loan')
                ->where('status', 'active')
                ->where('due_date', '<', Carbon::now())->count(),
            'monthly_income' => Transaction::income()
                ->whereMonth('transaction_date', Carbon::now()->month)
                ->sum('amount'),
            'monthly_expense' => Transaction::expense()
                ->whereMonth('transaction_date', Carbon::now()->month)
                ->sum('amount'),
            'monthly_unit_revenue' => Transaction::income()
                ->whereMonth('transaction_date', Carbon::now()->month)
                ->whereHas('businessUnit')
                ->sum('amount'),
            'active_units' => BusinessUnit::where('status', 'active')->count(),
            'pending_loans' => SavingsLoan::where('type', 'loan')
                ->where('status', 'pending')->count(),
        ];

        // Calculate profit
        $stats['monthly_profit'] = $stats['monthly_income'] - $stats['monthly_expense'];

        // Recent activities
        $recentTransactions = Transaction::with(['member', 'businessUnit'])
            ->orderBy('transaction_date', 'desc')
            ->limit(10)
            ->get();

        $pendingLoans = SavingsLoan::with('member')
            ->where('type', 'loan')
            ->where('status', 'pending')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        // Unit performance
        $unitPerformance = BusinessUnit::with('transactions')
            ->active()
            ->get()
            ->map(function ($unit) {
                $monthlyRevenue = $unit->transactions()
                    ->where('type', 'income')
                    ->whereMonth('transaction_date', Carbon::now()->month)
                    ->sum('amount');
                    
                return (object)[
                    'name' => $unit->name,
                    'type' => $unit->type,
                    'monthly_revenue' => $monthlyRevenue,
                    'revenue' => $unit->revenue,
                    'expenses' => $unit->expenses,
                    'profit' => $unit->profit,
                ];
            });

        // Monthly chart data (last 6 months)
        $monthlyChart = [
            'labels' => [],
            'data' => []
        ];
        
        for ($i = 5; $i >= 0; $i--) {
            $month = Carbon::now()->subMonths($i);
            $monthlyChart['labels'][] = $month->format('M Y');
            $monthlyChart['data'][] = Transaction::income()
                ->whereMonth('transaction_date', $month->month)
                ->whereYear('transaction_date', $month->year)
                ->sum('amount');
        }

        // Business sector distribution
        $sectorDistribution = Member::select('business_sector', DB::raw('count(*) as count'))
            ->where('status', 'active')
            ->groupBy('business_sector')
            ->get();

        return view('admin.dashboard.index', compact(
            'stats',
            'recentTransactions',
            'pendingLoans',
            'unitPerformance',
            'monthlyChart',
            'sectorDistribution'
        ));
    }
}
