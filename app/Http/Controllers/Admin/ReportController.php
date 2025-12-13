<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\FinancialReport;
use App\Models\Member;
use App\Models\SavingsLoan;
use App\Models\BusinessUnit;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:reports.view')->only(['index']);
        $this->middleware('permission:reports.financial')->only(['financial']);
        $this->middleware('permission:reports.members')->only(['members']);
        $this->middleware('permission:reports.units')->only(['units']);
        $this->middleware('permission:reports.generate')->only(['generate']);
        $this->middleware('permission:reports.export')->only(['export']);
        $this->middleware('permission:reports.personal')->only(['personal']);
    }
    public function financial(Request $request)
    {
        $period = $request->period ?? 'monthly';
        $year = $request->year ?? Carbon::now()->year;
        $month = $request->month ?? Carbon::now()->month;

        // Calculate period dates
        if ($period === 'monthly') {
            $startDate = Carbon::create($year, $month, 1);
            $endDate = $startDate->copy()->endOfMonth();
        } elseif ($period === 'quarterly') {
            $quarter = ceil($month / 3);
            $startMonth = ($quarter - 1) * 3 + 1;
            $startDate = Carbon::create($year, $startMonth, 1);
            $endDate = $startDate->copy()->addMonths(2)->endOfMonth();
        } else { // annual
            $startDate = Carbon::create($year, 1, 1);
            $endDate = $startDate->copy()->endOfYear();
        }

        // Financial data
        $income = Transaction::income()
            ->whereBetween('transaction_date', [$startDate, $endDate])
            ->sum('amount');

        $expense = Transaction::expense()
            ->whereBetween('transaction_date', [$startDate, $endDate])
            ->sum('amount');

        $netProfit = $income - $expense;

        // Savings and loans data
        $totalSavings = SavingsLoan::where('type', 'savings')
            ->where('status', 'completed')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->sum('amount');

        $totalLoans = SavingsLoan::where('type', 'loan')
            ->where('status', '!=', 'rejected')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->sum('amount');

        $activeLoanPortfolio = SavingsLoan::where('type', 'loan')
            ->where('status', 'active')
            ->sum('amount');

        // Member statistics
        $activeMembersCount = Member::where('status', 'active')->count();
        $newMembersCount = Member::whereBetween('join_date', [$startDate, $endDate])->count();

        // Unit performance
        $unitPerformance = BusinessUnit::with(['transactions' => function($q) use ($startDate, $endDate) {
                $q->whereBetween('transaction_date', [$startDate, $endDate]);
            }])
            ->active()
            ->get()
            ->map(function($unit) {
                return [
                    'name' => $unit->name,
                    'type' => $unit->type,
                    'revenue' => $unit->transactions->where('type', 'income')->sum('amount'),
                    'expenses' => $unit->transactions->where('type', 'expense')->sum('amount'),
                    'profit' => $unit->transactions->where('type', 'income')->sum('amount') - 
                               $unit->transactions->where('type', 'expense')->sum('amount'),
                ];
            });

        // Chart data
        $chartData = $this->getFinancialChartData($period, $year, $month);

        // Previous period comparison
        $previousPeriodData = $this->getPreviousPeriodComparison($period, $year, $month);

        return view('admin.reports.financial', compact(
            'period',
            'year',
            'month',
            'startDate',
            'endDate',
            'income',
            'expense',
            'netProfit',
            'totalSavings',
            'totalLoans',
            'activeLoanPortfolio',
            'activeMembersCount',
            'newMembersCount',
            'unitPerformance',
            'chartData',
            'previousPeriodData'
        ));
    }

    public function members(Request $request)
    {
        $period = $request->period ?? 'monthly';
        $year = $request->year ?? Carbon::now()->year;
        $month = $request->month ?? Carbon::now()->month;

        // Calculate period dates
        if ($period === 'monthly') {
            $startDate = Carbon::create($year, $month, 1);
            $endDate = $startDate->copy()->endOfMonth();
        } elseif ($period === 'quarterly') {
            $quarter = ceil($month / 3);
            $startMonth = ($quarter - 1) * 3 + 1;
            $startDate = Carbon::create($year, $startMonth, 1);
            $endDate = $startDate->copy()->addMonths(2)->endOfMonth();
        } else { // annual
            $startDate = Carbon::create($year, 1, 1);
            $endDate = $startDate->copy()->endOfYear();
        }

        // Member statistics
        $totalMembers = Member::count();
        $activeMembers = Member::where('status', 'active')->count();
        $newMembers = Member::whereBetween('join_date', [$startDate, $endDate])->count();
        $verifiedMembers = Member::whereNotNull('verified_at')->count();

        // Members by business sector
        $membersBySector = Member::select('business_sector', DB::raw('count(*) as count'))
            ->where('status', 'active')
            ->groupBy('business_sector')
            ->get();

        // Members by experience
        $membersByExperience = Member::select('experience', DB::raw('count(*) as count'))
            ->where('status', 'active')
            ->groupBy('experience')
            ->get();

        // Member growth trend
        $memberGrowth = $this->getMemberGrowthData($period, $year, $month);

        // Top savers
        $topSavers = Member::with(['savingsLoans' => function($q) {
                $q->where('type', 'savings')->where('status', 'completed');
            }])
            ->where('status', 'active')
            ->get()
            ->map(function($member) {
                return [
                    'member' => $member,
                    'total_savings' => $member->savingsLoans ? $member->savingsLoans->sum('amount') : 0,
                    'loan_count' => $member->savingsLoans ? $member->savingsLoans->where('type', 'loan')->count() : 0,
                ];
            })
            ->sortByDesc('total_savings')
            ->take(10);

        // Members with active loans
        $membersWithLoans = Member::with(['savingsLoans' => function($q) {
                $q->where('type', 'loan')->whereIn('status', ['active', 'overdue']);
            }])
            ->where('status', 'active')
            ->get()
            ->filter(function($member) {
                return $member->savingsLoans && $member->savingsLoans->count() > 0;
            });

        return view('admin.reports.members', compact(
            'period',
            'year',
            'month',
            'totalMembers',
            'activeMembers',
            'newMembers',
            'verifiedMembers',
            'membersBySector',
            'membersByExperience',
            'memberGrowth',
            'topSavers',
            'membersWithLoans'
        ));
    }

    public function units(Request $request)
    {
        $period = $request->period ?? 'monthly';
        $year = $request->year ?? Carbon::now()->year;
        $month = $request->month ?? Carbon::now()->month;

        // Calculate period dates
        if ($period === 'monthly') {
            $startDate = Carbon::create($year, $month, 1);
            $endDate = $startDate->copy()->endOfMonth();
        } elseif ($period === 'quarterly') {
            $quarter = ceil($month / 3);
            $startMonth = ($quarter - 1) * 3 + 1;
            $startDate = Carbon::create($year, $startMonth, 1);
            $endDate = $startDate->copy()->addMonths(2)->endOfMonth();
        } else { // annual
            $startDate = Carbon::create($year, 1, 1);
            $endDate = $startDate->copy()->endOfYear();
        }

        // Unit statistics
        $totalUnits = BusinessUnit::count();

        // Calculate totals for all units
        $allUnits = BusinessUnit::with(['transactions' => function($q) use ($startDate, $endDate) {
                $q->whereBetween('transaction_date', [$startDate, $endDate]);
            }])
            ->get();

        $totalRevenue = $allUnits->sum(function($unit) {
            return $unit->transactions->where('type', 'income')->sum('amount');
        });

        $totalExpenses = $allUnits->sum(function($unit) {
            return $unit->transactions->where('type', 'expense')->sum('amount');
        });

        $totalProfit = $totalRevenue - $totalExpenses;

        // Units by type with financial data
        $unitsByType = BusinessUnit::with(['transactions' => function($q) use ($startDate, $endDate) {
                $q->whereBetween('transaction_date', [$startDate, $endDate]);
            }])
            ->get()
            ->groupBy('type')
            ->map(function($units, $type) {
                $revenue = $units->sum(function($unit) {
                    return $unit->transactions->where('type', 'income')->sum('amount');
                });
                $expenses = $units->sum(function($unit) {
                    return $unit->transactions->where('type', 'expense')->sum('amount');
                });
                return [
                    'type' => $type,
                    'count' => $units->count(),
                    'revenue' => $revenue,
                    'expenses' => $expenses,
                    'profit' => $revenue - $expenses,
                ];
            });

        // Unit details for individual performance table
        $unitDetails = BusinessUnit::with(['transactions' => function($q) use ($startDate, $endDate) {
                $q->whereBetween('transaction_date', [$startDate, $endDate]);
            }])
            ->get()
            ->map(function($unit) {
                $revenue = $unit->transactions->where('type', 'income')->sum('amount');
                $expenses = $unit->transactions->where('type', 'expense')->sum('amount');
                $unit->revenue = $revenue;
                $unit->expenses = $expenses;
                return $unit;
            });

        // Top performing units
        $topUnits = $unitDetails
            ->map(function($unit) {
                return [
                    'name' => $unit->name,
                    'type' => $unit->type,
                    'location' => $unit->location,
                    'revenue' => $unit->revenue,
                    'expenses' => $unit->expenses,
                    'profit' => $unit->revenue - $unit->expenses,
                ];
            })
            ->sortByDesc('profit')
            ->take(5);

        // Chart data for performance trends
        $chartData = $this->getUnitsChartData($period, $year, $month);

        // Unit statistics for sidebar
        $unitStats = [
            'average_revenue' => $totalUnits > 0 ? $totalRevenue / $totalUnits : 0,
            'average_expenses' => $totalUnits > 0 ? $totalExpenses / $totalUnits : 0,
            'best_unit' => $topUnits->first()['name'] ?? '-',
            'average_margin' => $totalRevenue > 0 ? ($totalProfit / $totalRevenue) * 100 : 0,
        ];

        return view('admin.reports.units', compact(
            'period',
            'year',
            'month',
            'startDate',
            'endDate',
            'totalUnits',
            'totalRevenue',
            'totalExpenses',
            'totalProfit',
            'unitsByType',
            'unitDetails',
            'topUnits',
            'chartData',
            'unitStats'
        ));
    }

    public function generate(Request $request)
    {
        $validated = $request->validate([
            'type' => 'required|in:monthly,quarterly,annual,custom',
            'title' => 'required|string|max:255',
            'period_start' => 'required|date',
            'period_end' => 'required|date|after_or_equal:period_start',
            'include_charts' => 'boolean',
            'summary' => 'nullable|string',
        ]);

        // Calculate financial data
        $income = Transaction::income()
            ->whereBetween('transaction_date', [$validated['period_start'], $validated['period_end']])
            ->sum('amount');

        $expense = Transaction::expense()
            ->whereBetween('transaction_date', [$validated['period_start'], $validated['period_end']])
            ->sum('amount');

        // Create financial report
        $report = FinancialReport::create([
            'report_number' => 'RPT' . date('Ymd') . str_pad(FinancialReport::count() + 1, 4, '0', STR_PAD_LEFT),
            'type' => $validated['type'],
            'title' => $validated['title'],
            'period_start' => $validated['period_start'],
            'period_end' => $validated['period_end'],
            'total_income' => $income,
            'total_expense' => $expense,
            'net_profit' => $income - $expense,
            'total_savings' => SavingsLoan::where('type', 'savings')
                ->where('status', 'completed')
                ->whereBetween('created_at', [$validated['period_start'], $validated['period_end']])
                ->sum('amount'),
            'total_loans' => SavingsLoan::where('type', 'loan')
                ->where('status', '!=', 'rejected')
                ->whereBetween('created_at', [$validated['period_start'], $validated['period_end']])
                ->sum('amount'),
            'loan_portfolio' => SavingsLoan::where('type', 'loan')
                ->where('status', 'active')
                ->sum('amount'),
            'active_members_count' => Member::where('status', 'active')->count(),
            'unit_performance' => $this->getUnitPerformanceData($validated['period_start'], $validated['period_end']),
            'charts_data' => $validated['include_charts'] ? $this->getChartsData($validated['period_start'], $validated['period_end']) : null,
            'summary' => $validated['summary'],
            'generated_by' => auth()->id(),
            'generated_at' => Carbon::now(),
        ]);

        return redirect()
            ->route('admin.reports.show', $report)
            ->with('success', 'Laporan berhasil dibuat');
    }

    public function show(FinancialReport $report)
    {
        $report->load('generatedBy');

        return view('admin.reports.show', compact('report'));
    }

    public function index(Request $request)
    {
        $query = FinancialReport::with('generatedBy');

        // Filter by type
        if ($request->type) {
            $query->where('type', $request->type);
        }

        // Filter by year
        if ($request->year) {
            $query->whereYear('period_start', $request->year);
        }

        $reports = $query->latest('generated_at')->paginate(15);

        return view('admin.reports.index', compact('reports'));
    }

    private function getFinancialChartData($period, $year, $month)
    {
        if ($period === 'monthly') {
            // Daily data for the month
            $data = [];
            for ($day = 1; $day <= Carbon::create($year, $month)->daysInMonth; $day++) {
                $date = Carbon::create($year, $month, $day);
                $data[] = [
                    'date' => $day,
                    'income' => Transaction::income()
                        ->whereDate('transaction_date', $date)
                        ->sum('amount'),
                    'expense' => Transaction::expense()
                        ->whereDate('transaction_date', $date)
                        ->sum('amount'),
                ];
            }
            return $data;
        } elseif ($period === 'quarterly') {
            // Monthly data for the quarter
            $quarter = ceil($month / 3);
            $startMonth = ($quarter - 1) * 3 + 1;
            $data = [];
            for ($i = 0; $i < 3; $i++) {
                $currentMonth = $startMonth + $i;
                $data[] = [
                    'month' => Carbon::create($year, $currentMonth)->format('M'),
                    'income' => Transaction::income()
                        ->whereMonth('transaction_date', $currentMonth)
                        ->whereYear('transaction_date', $year)
                        ->sum('amount'),
                    'expense' => Transaction::expense()
                        ->whereMonth('transaction_date', $currentMonth)
                        ->whereYear('transaction_date', $year)
                        ->sum('amount'),
                ];
            }
            return $data;
        } else {
            // Monthly data for the year
            $data = [];
            for ($i = 1; $i <= 12; $i++) {
                $data[] = [
                    'month' => Carbon::create($year, $i)->format('M'),
                    'income' => Transaction::income()
                        ->whereMonth('transaction_date', $i)
                        ->whereYear('transaction_date', $year)
                        ->sum('amount'),
                    'expense' => Transaction::expense()
                        ->whereMonth('transaction_date', $i)
                        ->whereYear('transaction_date', $year)
                        ->sum('amount'),
                ];
            }
            return $data;
        }
    }

    private function getPreviousPeriodComparison($period, $year, $month)
    {
        if ($period === 'monthly') {
            $prevDate = Carbon::create($year, $month)->subMonth();
        } elseif ($period === 'quarterly') {
            $prevDate = Carbon::create($year, $month)->subMonths(3);
        } else {
            $prevDate = Carbon::create($year - 1, 1, 1);
        }

        $income = Transaction::income()
            ->whereMonth('transaction_date', $prevDate->month)
            ->whereYear('transaction_date', $prevDate->year)
            ->sum('amount');

        $expense = Transaction::expense()
            ->whereMonth('transaction_date', $prevDate->month)
            ->whereYear('transaction_date', $prevDate->year)
            ->sum('amount');

        return [
            'income' => $income,
            'expense' => $expense,
            'profit' => $income - $expense,
        ];
    }

    private function getMemberGrowthData($period, $year, $month)
    {
        if ($period === 'monthly') {
            return Member::selectRaw('DAY(join_date) as day, COUNT(*) as count')
                ->whereMonth('join_date', $month)
                ->whereYear('join_date', $year)
                ->groupBy('day')
                ->orderBy('day')
                ->get();
        } else {
            return Member::selectRaw('MONTH(join_date) as month, COUNT(*) as count')
                ->whereYear('join_date', $year)
                ->groupBy('month')
                ->orderBy('month')
                ->get();
        }
    }

    private function getUnitPerformanceTrend($year)
    {
        return BusinessUnit::with(['transactions' => function($q) use ($year) {
                $q->whereYear('transaction_date', $year);
            }])
            ->active()
            ->get()
            ->map(function($unit) use ($year) {
                $monthlyData = [];
                for ($i = 1; $i <= 12; $i++) {
                    $monthlyData[] = $unit->transactions()
                        ->whereMonth('transaction_date', $i)
                        ->whereYear('transaction_date', $year)
                        ->get()
                        ->sum(function($transaction) {
                            return $transaction->type === 'income' ? $transaction->amount : -$transaction->amount;
                        });
                }
                return [
                    'name' => $unit->name,
                    'data' => $monthlyData,
                ];
            });
    }

    private function getUnitPerformanceData($startDate, $endDate)
    {
        return BusinessUnit::with(['transactions' => function($q) use ($startDate, $endDate) {
                $q->whereBetween('transaction_date', [$startDate, $endDate]);
            }])
            ->active()
            ->get()
            ->map(function($unit) {
                return [
                    'name' => $unit->name,
                    'type' => $unit->type,
                    'revenue' => $unit->transactions->where('type', 'income')->sum('amount'),
                    'expenses' => $unit->transactions->where('type', 'expense')->sum('amount'),
                    'profit' => $unit->transactions->where('type', 'income')->sum('amount') - 
                               $unit->transactions->where('type', 'expense')->sum('amount'),
                ];
            });
    }

    private function getUnitsChartData($period, $year, $month)
    {
        if ($period === 'monthly') {
            // Daily data for the month
            $data = [];
            for ($day = 1; $day <= Carbon::create($year, $month)->daysInMonth; $day++) {
                $date = Carbon::create($year, $month, $day);
                $dayRevenue = Transaction::income()
                    ->whereDate('transaction_date', $date)
                    ->whereHas('businessUnit')
                    ->sum('amount');
                $dayExpenses = Transaction::expense()
                    ->whereDate('transaction_date', $date)
                    ->whereHas('businessUnit')
                    ->sum('amount');
                $dayProfit = $dayRevenue - $dayExpenses;
                
                $data['labels'][] = $day;
                $data['revenue'][] = $dayRevenue;
                $data['expenses'][] = $dayExpenses;
                $data['profit'][] = $dayProfit;
            }
        } elseif ($period === 'quarterly') {
            // Monthly data for the quarter
            $quarter = ceil($month / 3);
            $startMonth = ($quarter - 1) * 3 + 1;
            $data = [];
            for ($i = 0; $i < 3; $i++) {
                $currentMonth = $startMonth + $i;
                $monthRevenue = Transaction::income()
                    ->whereMonth('transaction_date', $currentMonth)
                    ->whereYear('transaction_date', $year)
                    ->whereHas('businessUnit')
                    ->sum('amount');
                $monthExpenses = Transaction::expense()
                    ->whereMonth('transaction_date', $currentMonth)
                    ->whereYear('transaction_date', $year)
                    ->whereHas('businessUnit')
                    ->sum('amount');
                $monthProfit = $monthRevenue - $monthExpenses;
                
                $data['labels'][] = Carbon::create($year, $currentMonth)->format('M');
                $data['revenue'][] = $monthRevenue;
                $data['expenses'][] = $monthExpenses;
                $data['profit'][] = $monthProfit;
            }
        } else {
            // Monthly data for the year
            $data = [];
            for ($i = 1; $i <= 12; $i++) {
                $monthRevenue = Transaction::income()
                    ->whereMonth('transaction_date', $i)
                    ->whereYear('transaction_date', $year)
                    ->whereHas('businessUnit')
                    ->sum('amount');
                $monthExpenses = Transaction::expense()
                    ->whereMonth('transaction_date', $i)
                    ->whereYear('transaction_date', $year)
                    ->whereHas('businessUnit')
                    ->sum('amount');
                $monthProfit = $monthRevenue - $monthExpenses;
                
                $data['labels'][] = Carbon::create($year, $i)->format('M');
                $data['revenue'][] = $monthRevenue;
                $data['expenses'][] = $monthExpenses;
                $data['profit'][] = $monthProfit;
            }
        }
        
        return $data;
    }

    private function getChartsData($startDate, $endDate)
    {
        return [
            'monthly_trend' => $this->getFinancialChartData('monthly', $startDate->year, $startDate->month),
            'category_breakdown' => Transaction::selectRaw('category, type, SUM(amount) as total')
                ->whereBetween('transaction_date', [$startDate, $endDate])
                ->groupBy('category', 'type')
                ->get(),
        ];
    }
}
