<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\Member;
use App\Models\BusinessUnit;
use App\Models\SavingsLoan;
use Carbon\Carbon;

class TransactionController extends Controller
{
    public function index(Request $request)
    {
        $query = Transaction::with(['member', 'businessUnit', 'savingsLoan', 'recordedBy']);

        // Search
        if ($request->search) {
            $query->where(function($q) use ($request) {
                $q->where('transaction_number', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%')
                  ->orWhereHas('member', function($memberQuery) use ($request) {
                      $memberQuery->where('full_name', 'like', '%' . $request->search . '%')
                                ->orWhere('member_number', 'like', '%' . $request->search . '%');
                  });
            });
        }

        // Filter by type
        if ($request->type) {
            $query->where('type', $request->type);
        }

        // Filter by category
        if ($request->category) {
            $query->where('category', $request->category);
        }

        // Filter by date range
        if ($request->date_from) {
            $query->whereDate('transaction_date', '>=', $request->date_from);
        }
        if ($request->date_to) {
            $query->whereDate('transaction_date', '<=', $request->date_to);
        }

        // Filter by payment method
        if ($request->payment_method) {
            $query->where('payment_method', $request->payment_method);
        }

        $transactions = $query->orderBy('transaction_date', 'desc')->paginate(20);

        // Statistics
        $stats = [
            'total_income' => Transaction::income()
                ->whereMonth('transaction_date', Carbon::now()->month)
                ->sum('amount'),
            'total_expense' => Transaction::expense()
                ->whereMonth('transaction_date', Carbon::now()->month)
                ->sum('amount'),
            'monthly_profit' => Transaction::income()
                ->whereMonth('transaction_date', Carbon::now()->month)
                ->sum('amount') - Transaction::expense()
                ->whereMonth('transaction_date', Carbon::now()->month)
                ->sum('amount'),
            'total_transactions' => Transaction::whereMonth('transaction_date', Carbon::now()->month)->count(),
        ];

        // Categories for filter
        $categories = Transaction::select('category')->distinct()->pluck('category');

        return view('admin.transactions.index', compact('transactions', 'stats', 'categories'));
    }

    public function create()
    {
        $members = Member::where('status', 'active')->orderBy('full_name')->get();
        $businessUnits = BusinessUnit::where('status', 'active')->orderBy('name')->get();
        $categories = [
            'savings_deposit' => 'Simpanan Masuk',
            'savings_withdrawal' => 'Simpanan Keluar',
            'loan_disbursement' => 'Pencairan Pinjaman',
            'loan_payment' => 'Pembayaran Pinjaman',
            'unit_revenue' => 'Pendapatan Unit',
            'unit_expense' => 'Pengeluaran Unit',
            'operational_cost' => 'Biaya Operasional',
            'other' => 'Lainnya',
        ];

        return view('admin.transactions.create', compact('members', 'businessUnits', 'categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'type' => 'required|in:income,expense',
            'category' => 'required|string',
            'amount' => 'required|numeric|min:0',
            'description' => 'required|string|max:255',
            'transaction_date' => 'required|date',
            'member_id' => 'nullable|exists:members,id',
            'business_unit_id' => 'nullable|exists:business_units,id',
            'savings_loan_id' => 'nullable|exists:savings_loans,id',
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
            'member_id' => $validated['member_id'],
            'business_unit_id' => $validated['business_unit_id'],
            'savings_loan_id' => $validated['savings_loan_id'],
            'recorded_by' => auth()->id(),
            'payment_method' => $validated['payment_method'],
            'reference_number' => $validated['reference_number'],
            'notes' => $validated['notes'],
        ]);

        return redirect()
            ->route('admin.transactions.show', $transaction)
            ->with('success', 'Transaksi berhasil dicatat');
    }

    public function show(Transaction $transaction)
    {
        $transaction->load(['member', 'businessUnit', 'savingsLoan', 'recordedBy']);

        return view('admin.transactions.show', compact('transaction'));
    }

    public function edit(Transaction $transaction)
    {
        // Only allow editing of recent transactions (within 24 hours)
        if ($transaction->created_at->diffInHours(now()) > 24) {
            return redirect()
                ->route('admin.transactions.show', $transaction)
                ->with('error', 'Transaksi yang lebih dari 24 jam tidak dapat diedit');
        }

        $transaction->load(['member', 'businessUnit', 'savingsLoan']);
        $members = Member::where('status', 'active')->orderBy('full_name')->get();
        $businessUnits = BusinessUnit::where('status', 'active')->orderBy('name')->get();
        $categories = [
            'savings_deposit' => 'Simpanan Masuk',
            'savings_withdrawal' => 'Simpanan Keluar',
            'loan_disbursement' => 'Pencairan Pinjaman',
            'loan_payment' => 'Pembayaran Pinjaman',
            'unit_revenue' => 'Pendapatan Unit',
            'unit_expense' => 'Pengeluaran Unit',
            'operational_cost' => 'Biaya Operasional',
            'other' => 'Lainnya',
        ];

        return view('admin.transactions.edit', compact('transaction', 'members', 'businessUnits', 'categories'));
    }

    public function update(Request $request, Transaction $transaction)
    {
        // Only allow editing of recent transactions (within 24 hours)
        if ($transaction->created_at->diffInHours(now()) > 24) {
            return redirect()
                ->route('admin.transactions.show', $transaction)
                ->with('error', 'Transaksi yang lebih dari 24 jam tidak dapat diedit');
        }

        $validated = $request->validate([
            'type' => 'required|in:income,expense',
            'category' => 'required|string',
            'amount' => 'required|numeric|min:0',
            'description' => 'required|string|max:255',
            'transaction_date' => 'required|date',
            'member_id' => 'nullable|exists:members,id',
            'business_unit_id' => 'nullable|exists:business_units,id',
            'savings_loan_id' => 'nullable|exists:savings_loans,id',
            'payment_method' => 'nullable|string',
            'reference_number' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        $transaction->update($validated);

        return redirect()
            ->route('admin.transactions.show', $transaction)
            ->with('success', 'Transaksi berhasil diperbarui');
    }

    public function destroy(Transaction $transaction)
    {
        // Only allow deletion of recent transactions (within 24 hours)
        if ($transaction->created_at->diffInHours(now()) > 24) {
            return redirect()
                ->route('admin.transactions.show', $transaction)
                ->with('error', 'Transaksi yang lebih dari 24 jam tidak dapat dihapus');
        }

        $transaction->delete();

        return redirect()
            ->route('admin.transactions.index')
            ->with('success', 'Transaksi berhasil dihapus');
    }

    public function daily(Request $request)
    {
        $date = $request->date ?? Carbon::today()->format('Y-m-d');
        
        $transactions = Transaction::with(['member', 'businessUnit'])
            ->whereDate('transaction_date', $date)
            ->orderBy('created_at', 'desc')
            ->get();

        $dailyStats = [
            'income' => $transactions->where('type', 'income')->sum('amount'),
            'expense' => $transactions->where('type', 'expense')->sum('amount'),
            'profit' => $transactions->where('type', 'income')->sum('amount') - $transactions->where('type', 'expense')->sum('amount'),
            'count' => $transactions->count(),
        ];

        return view('admin.transactions.daily', compact('transactions', 'dailyStats', 'date'));
    }

    public function monthly(Request $request)
    {
        $month = $request->month ?? Carbon::now()->month;
        $year = $request->year ?? Carbon::now()->year;

        $transactions = Transaction::with(['member', 'businessUnit'])
            ->whereMonth('transaction_date', $month)
            ->whereYear('transaction_date', $year)
            ->orderBy('transaction_date', 'desc')
            ->paginate(50);

        $monthlyStats = [
            'income' => Transaction::income()
                ->whereMonth('transaction_date', $month)
                ->whereYear('transaction_date', $year)
                ->sum('amount'),
            'expense' => Transaction::expense()
                ->whereMonth('transaction_date', $month)
                ->whereYear('transaction_date', $year)
                ->sum('amount'),
            'profit' => Transaction::income()
                ->whereMonth('transaction_date', $month)
                ->whereYear('transaction_date', $year)
                ->sum('amount') - Transaction::expense()
                ->whereMonth('transaction_date', $month)
                ->whereYear('transaction_date', $year)
                ->sum('amount'),
            'count' => Transaction::whereMonth('transaction_date', $month)
                ->whereYear('transaction_date', $year)
                ->count(),
        ];

        // Daily breakdown
        $dailyBreakdown = Transaction::selectRaw('DAY(transaction_date) as day, type, SUM(amount) as total, COUNT(*) as count')
            ->whereMonth('transaction_date', $month)
            ->whereYear('transaction_date', $year)
            ->groupBy('day', 'type')
            ->orderBy('day')
            ->get()
            ->groupBy('day');

        return view('admin.transactions.monthly', compact('transactions', 'monthlyStats', 'dailyBreakdown', 'month', 'year'));
    }

    public function export(Request $request)
    {
        $validated = $request->validate([
            'date_from' => 'required|date',
            'date_to' => 'required|date|after_or_equal:date_from',
            'format' => 'required|in:csv,xlsx',
        ]);

        $transactions = Transaction::with(['member', 'businessUnit', 'recordedBy'])
            ->whereDate('transaction_date', '>=', $validated['date_from'])
            ->whereDate('transaction_date', '<=', $validated['date_to'])
            ->orderBy('transaction_date', 'desc')
            ->get();

        if ($validated['format'] === 'csv') {
            $filename = 'transactions_' . $validated['date_from'] . '_to_' . $validated['date_to'] . '.csv';
            
            $headers = [
                'Content-Type' => 'text/csv',
                'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            ];

            $callback = function() use ($transactions) {
                $file = fopen('php://output', 'w');
                
                // CSV Header
                fputcsv($file, [
                    'Tanggal', 'No. Transaksi', 'Tipe', 'Kategori', 
                    'Jumlah', 'Deskripsi', 'Anggota', 'Unit Usaha', 
                    'Metode Pembayaran', 'No. Referensi', 'Dicatat Oleh'
                ]);

                // CSV Data
                foreach ($transactions as $transaction) {
                    fputcsv($file, [
                        $transaction->transaction_date->format('d/m/Y'),
                        $transaction->transaction_number,
                        $transaction->type === 'income' ? 'Pemasukan' : 'Pengeluaran',
                        $transaction->category,
                        number_format($transaction->amount, 2, ',', '.'),
                        $transaction->description,
                        $transaction->member?->full_name ?? '-',
                        $transaction->businessUnit?->name ?? '-',
                        $transaction->payment_method ?? '-',
                        $transaction->reference_number ?? '-',
                        $transaction->recordedBy->name,
                    ]);
                }

                fclose($file);
            };

            return response()->stream($callback, 200, $headers);
        }

        // Excel export would require additional package
        return redirect()
            ->back()
            ->with('error', 'Export Excel memerlukan library tambahan');
    }
}
