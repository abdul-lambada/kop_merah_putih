<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\BusinessUnit;
use App\Models\Transaction;
use Carbon\Carbon;

class BusinessUnitController extends Controller
{
    public function index(Request $request)
    {
        $query = BusinessUnit::with(['transactions' => function($q) {
            $q->orderBy('transaction_date', 'desc')->limit(10);
        }]);

        // Filter by type
        if ($request->type) {
            $query->where('type', $request->type);
        }

        // Filter by status
        if ($request->status) {
            $query->where('status', $request->status);
        }

        // Search
        if ($request->search) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('manager_name', 'like', '%' . $request->search . '%');
            });
        }

        $units = $query->orderBy('name')->paginate(15);

        // Statistics by type
        $statsByType = BusinessUnit::selectRaw('type, COUNT(*) as count, SUM(initial_capital) as total_capital, SUM(current_balance) as total_balance')
            ->groupBy('type')
            ->get()
            ->keyBy('type');

        return view('admin.units.index', compact('units', 'statsByType'));
    }

    public function create()
    {
        return view('admin.units.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:sembako,apotek,klinik,logistik',
            'description' => 'nullable|string',
            'address' => 'required|string',
            'phone' => 'nullable|string|max:20',
            'manager_name' => 'required|string|max:255',
            'initial_capital' => 'required|numeric|min:0',
            'operating_hours' => 'nullable|array',
            'notes' => 'nullable|string',
        ]);

        $validated['status'] = 'active';
        $validated['current_balance'] = $validated['initial_capital'];

        $unit = BusinessUnit::create($validated);

        return redirect()
            ->route('admin.units.show', $unit)
            ->with('success', 'Unit usaha berhasil dibuat');
    }

    public function show(BusinessUnit $unit)
    {
        $unit->load(['transactions' => function($q) {
            $q->orderBy('transaction_date', 'desc')->limit(20);
        }]);

        // Financial summary
        $revenue = $unit->revenue;
        $expenses = $unit->expenses;
        $profit = $unit->profit;
        $monthlyRevenue = $unit->monthlyRevenue;

        // Recent transactions
        $recentTransactions = $unit->transactions->take(10);

        // Monthly performance (last 6 months)
        $monthlyPerformance = [];
        for ($i = 5; $i >= 0; $i--) {
            $month = Carbon::now()->subMonths($i);
            $monthlyPerformance[] = [
                'month' => $month->format('M Y'),
                'revenue' => $unit->transactions()
                    ->where('type', 'income')
                    ->whereMonth('transaction_date', $month->month)
                    ->whereYear('transaction_date', $month->year)
                    ->sum('amount'),
                'expenses' => $unit->transactions()
                    ->where('type', 'expense')
                    ->whereMonth('transaction_date', $month->month)
                    ->whereYear('transaction_date', $month->year)
                    ->sum('amount'),
            ];
        }

        return view('admin.units.show', compact(
            'unit',
            'revenue',
            'expenses',
            'profit',
            'monthlyRevenue',
            'recentTransactions',
            'monthlyPerformance'
        ));
    }

    public function edit(BusinessUnit $unit)
    {
        return view('admin.units.edit', compact('unit'));
    }

    public function update(Request $request, BusinessUnit $unit)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'address' => 'required|string',
            'phone' => 'nullable|string|max:20',
            'manager_name' => 'required|string|max:255',
            'status' => 'required|in:active,inactive',
            'operating_hours' => 'nullable|array',
            'notes' => 'nullable|string',
        ]);

        $unit->update($validated);

        return redirect()
            ->route('admin.units.show', $unit)
            ->with('success', 'Data unit usaha berhasil diperbarui');
    }

    public function destroy(BusinessUnit $unit)
    {
        // Check if unit has transactions
        $transactionCount = $unit->transactions()->count();
        if ($transactionCount > 0) {
            return redirect()
                ->route('admin.units.show', $unit)
                ->with('error', 'Tidak dapat menghapus unit yang memiliki transaksi');
        }

        $unit->delete();

        return redirect()
            ->route('admin.units.index')
            ->with('success', 'Unit usaha berhasil dihapus');
    }

    // Specific unit type views
    public function sembako()
    {
        $units = BusinessUnit::byType('sembako')->with('transactions')->get();
        
        $stats = [
            'total_units' => $units->count(),
            'active_units' => $units->where('status', 'active')->count(),
            'total_revenue' => $units->sum('revenue'),
            'total_expenses' => $units->sum('expenses'),
            'total_profit' => $units->sum('profit'),
        ];

        return view('admin.units.sembako', compact('units', 'stats'));
    }

    public function apotek()
    {
        $units = BusinessUnit::byType('apotek')->with('transactions')->get();
        
        $stats = [
            'total_units' => $units->count(),
            'active_units' => $units->where('status', 'active')->count(),
            'total_revenue' => $units->sum('revenue'),
            'total_expenses' => $units->sum('expenses'),
            'total_profit' => $units->sum('profit'),
        ];

        return view('admin.units.apotek', compact('units', 'stats'));
    }

    public function klinik()
    {
        $units = BusinessUnit::byType('klinik')->with('transactions')->get();
        
        $stats = [
            'total_units' => $units->count(),
            'active_units' => $units->where('status', 'active')->count(),
            'total_revenue' => $units->sum('revenue'),
            'total_expenses' => $units->sum('expenses'),
            'total_profit' => $units->sum('profit'),
        ];

        return view('admin.units.klinik', compact('units', 'stats'));
    }

    public function logistik()
    {
        $units = BusinessUnit::byType('logistik')->with('transactions')->get();
        
        $stats = [
            'total_units' => $units->count(),
            'active_units' => $units->where('status', 'active')->count(),
            'total_revenue' => $units->sum('revenue'),
            'total_expenses' => $units->sum('expenses'),
            'total_profit' => $units->sum('profit'),
        ];

        return view('admin.units.logistik', compact('units', 'stats'));
    }

    public function transaction(Request $request, BusinessUnit $unit)
    {
        $validated = $request->validate([
            'type' => 'required|in:income,expense',
            'category' => 'required|string',
            'amount' => 'required|numeric|min:0',
            'description' => 'required|string',
            'transaction_date' => 'required|date',
            'payment_method' => 'nullable|string',
            'reference_number' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        $transaction = Transaction::create([
            'transaction_number' => 'TRX' . date('YmdHis') . rand(100, 999),
            'type' => $validated['type'],
            'category' => $validated['category'],
            'amount' => $validated['amount'],
            'description' => $validated['description'],
            'transaction_date' => $validated['transaction_date'],
            'business_unit_id' => $unit->id,
            'recorded_by' => auth()->id(),
            'payment_method' => $validated['payment_method'],
            'reference_number' => $validated['reference_number'],
            'notes' => $validated['notes'],
        ]);

        // Update unit balance
        if ($validated['type'] === 'income') {
            $unit->increment('current_balance', $validated['amount']);
        } else {
            $unit->decrement('current_balance', $validated['amount']);
        }

        return redirect()
            ->route('admin.units.show', $unit)
            ->with('success', 'Transaksi berhasil dicatat');
    }

    public function report(BusinessUnit $unit)
    {
        // Monthly performance
        $monthlyData = Transaction::where('business_unit_id', $unit->id)
            ->selectRaw('MONTH(transaction_date) as month, YEAR(transaction_date) as year, type, SUM(amount) as total')
            ->whereYear('transaction_date', Carbon::now()->year)
            ->groupBy('year', 'month', 'type')
            ->orderBy('year', 'desc')
            ->orderBy('month', 'desc')
            ->get()
            ->groupBy(['year', 'month']);

        // Top categories
        $topCategories = Transaction::where('business_unit_id', $unit->id)
            ->selectRaw('category, type, COUNT(*) as count, SUM(amount) as total')
            ->groupBy('category', 'type')
            ->orderBy('total', 'desc')
            ->limit(10)
            ->get();

        // Daily trend (last 30 days)
        $dailyTrend = [];
        for ($i = 29; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $dailyTrend[] = [
                'date' => $date->format('d/m'),
                'income' => Transaction::where('business_unit_id', $unit->id)
                    ->where('type', 'income')
                    ->whereDate('transaction_date', $date)
                    ->sum('amount'),
                'expense' => Transaction::where('business_unit_id', $unit->id)
                    ->where('type', 'expense')
                    ->whereDate('transaction_date', $date)
                    ->sum('amount'),
            ];
        }

        return view('admin.units.report', compact(
            'unit',
            'monthlyData',
            'topCategories',
            'dailyTrend'
        ));
    }
}
