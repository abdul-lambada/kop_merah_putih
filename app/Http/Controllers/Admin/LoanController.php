<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SavingsLoan;
use App\Models\Member;
use App\Models\Transaction;
use Illuminate\Support\Str;
use Carbon\Carbon;

class LoanController extends Controller
{
    public function index(Request $request)
    {
        $query = SavingsLoan::with('member')
            ->where('type', 'loan');

        // Search
        if ($request->search) {
            $query->where(function($q) use ($request) {
                $q->where('transaction_number', 'like', '%' . $request->search . '%')
                  ->orWhereHas('member', function($memberQuery) use ($request) {
                      $memberQuery->where('full_name', 'like', '%' . $request->search . '%')
                                ->orWhere('member_number', 'like', '%' . $request->search . '%');
                  });
            });
        }

        // Filter by status
        if ($request->status) {
            $query->where('status', $request->status);
        }

        // Filter by date range
        if ($request->date_from) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->date_to) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $loans = $query->orderBy('created_at', 'desc')->paginate(15);

        // Statistics
        $stats = [
            'total_loans' => SavingsLoan::where('type', 'loan')
                ->whereIn('status', ['active', 'completed'])->sum('amount'),
            'active_portfolio' => SavingsLoan::where('type', 'loan')
                ->where('status', 'active')->sum('amount'),
            'this_month_loans' => SavingsLoan::where('type', 'loan')
                ->whereMonth('created_at', Carbon::now()->month)
                ->sum('amount'),
            'monthly_loans' => SavingsLoan::where('type', 'loan')
                ->whereMonth('created_at', Carbon::now()->month)
                ->sum('amount'),
            'monthly_count' => SavingsLoan::where('type', 'loan')
                ->whereMonth('created_at', Carbon::now()->month)
                ->count(),
            'pending_loans' => SavingsLoan::where('type', 'loan')
                ->where('status', 'pending')->count(),
            'pending_count' => SavingsLoan::where('type', 'loan')
                ->where('status', 'pending')->count(),
            'pending_amount' => SavingsLoan::where('type', 'loan')
                ->where('status', 'pending')->sum('amount'),
            'active_loans' => SavingsLoan::where('type', 'loan')
                ->where('status', 'active')->count(),
            'active_count' => SavingsLoan::where('type', 'loan')
                ->where('status', 'active')->count(),
            'overdue_loans' => SavingsLoan::where('type', 'loan')
                ->where('status', 'active')
                ->where('due_date', '<', Carbon::today())
                ->count(),
            'overdue_count' => SavingsLoan::where('type', 'loan')
                ->where('status', 'active')
                ->where('due_date', '<', Carbon::today())
                ->count(),
            'overdue_amount' => SavingsLoan::where('type', 'loan')
                ->where('status', 'active')
                ->where('due_date', '<', Carbon::today())
                ->sum('amount'),
            'total_outstanding' => SavingsLoan::where('type', 'loan')
                ->where('status', 'active')
                ->get()
                ->sum('remaining_balance'),
        ];

        return view('admin.loans.index', compact('loans', 'stats'));
    }

    public function create()
    {
        $members = Member::where('status', 'active')
            ->orderBy('full_name')
            ->get();

        return view('admin.loans.create', compact('members'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'member_id' => 'required|exists:members,id',
            'amount' => 'required|numeric|min:100000',
            'interest_rate' => 'required|numeric|min:0|max:30',
            'tenure_months' => 'required|integer|min:1|max:60',
            'purpose' => 'required|string|max:255',
            'notes' => 'nullable|string',
        ]);

        $member = Member::findOrFail($validated['member_id']);

        // Check loan limit
        $activeLoans = $member->activeLoans;
        $currentLoanAmount = $activeLoans->sum('amount') + $validated['amount'];
        
        if ($currentLoanAmount > $member->loan_limit) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Total pinjaman melebihi limit (' . number_format($member->loan_limit, 0, ',', '.') . ')');
        }

        // Calculate monthly installment
        $principal = $validated['amount'];
        $monthlyRate = $validated['interest_rate'] / 100 / 12;
        $months = $validated['tenure_months'];
        
        if ($monthlyRate == 0) {
            $monthlyInstallment = $principal / $months;
        } else {
            $monthlyInstallment = $principal * ($monthlyRate * pow(1 + $monthlyRate, $months)) / (pow(1 + $monthlyRate, $months) - 1);
        }

        // Generate transaction number
        $transactionNumber = 'LOAN' . date('Ymd') . str_pad(SavingsLoan::count() + 1, 4, '0', STR_PAD_LEFT);

        $loan = SavingsLoan::create([
            'member_id' => $validated['member_id'],
            'type' => 'loan',
            'transaction_number' => $transactionNumber,
            'amount' => $validated['amount'],
            'interest_rate' => $validated['interest_rate'],
            'tenure_months' => $validated['tenure_months'],
            'monthly_installment' => round($monthlyInstallment, 2),
            'due_date' => Carbon::now()->addMonths($validated['tenure_months']),
            'status' => 'pending',
            'purpose' => $validated['purpose'],
            'notes' => $validated['notes'],
        ]);

        return redirect()
            ->route('admin.loans.show', $loan)
            ->with('success', 'Pengajuan pinjaman berhasil dibuat');
    }

    public function show(SavingsLoan $loan)
    {
        $loan->load(['member', 'transactions', 'approvedBy']);

        $paymentHistory = $loan->transactions()
            ->where('category', 'loan_payment')
            ->orderBy('transaction_date', 'desc')
            ->get();

        $remainingBalance = $loan->remaining_balance;
        $isOverdue = $loan->is_overdue;

        return view('admin.loans.show', compact(
            'loan',
            'paymentHistory',
            'remainingBalance',
            'isOverdue'
        ));
    }

    public function approve(SavingsLoan $loan)
    {
        if ($loan->status !== 'pending') {
            return redirect()
                ->route('admin.loans.show', $loan)
                ->with('error', 'Hanya pinjaman dengan status pending yang dapat disetujui');
        }

        // Check if user has permission for this loan amount
        $loanAmount = $loan->amount;
        $userMaxApproval = $this->getUserMaxApprovalAmount();

        if ($loanAmount > $userMaxApproval) {
            return redirect()
                ->route('admin.loans.show', $loan)
                ->with('error', 'Anda tidak memiliki otoritas untuk menyetujui pinjaman sebesar ini');
        }

        $loan->update([
            'status' => 'active',
            'approved_by' => auth()->id(),
            'approved_at' => Carbon::now(),
        ]);

        // Create disbursement transaction
        Transaction::create([
            'transaction_number' => 'TRX' . date('YmdHis') . rand(100, 999),
            'type' => 'expense',
            'category' => 'loan_disbursement',
            'amount' => $loan->amount,
            'description' => "Pencairan pinjaman kepada {$loan->member->full_name}",
            'transaction_date' => Carbon::today(),
            'member_id' => $loan->member_id,
            'savings_loan_id' => $loan->id,
            'recorded_by' => auth()->id(),
            'payment_method' => 'cash',
        ]);

        return redirect()
            ->route('admin.loans.show', $loan)
            ->with('success', 'Pinjaman berhasil disetujui dan dicairkan');
    }

    public function reject(SavingsLoan $loan, Request $request)
    {
        $validated = $request->validate([
            'rejection_reason' => 'required|string|max:255',
        ]);

        if ($loan->status !== 'pending') {
            return redirect()
                ->route('admin.loans.show', $loan)
                ->with('error', 'Hanya pinjaman dengan status pending yang dapat ditolak');
        }

        $loan->update([
            'status' => 'rejected',
            'notes' => $validated['rejection_reason'],
        ]);

        return redirect()
            ->route('admin.loans.show', $loan)
            ->with('success', 'Pinjaman ditolak');
    }

    public function payment(Request $request, SavingsLoan $loan)
    {
        $validated = $request->validate([
            'amount' => 'required|numeric|min:1000',
            'payment_method' => 'required|string',
            'notes' => 'nullable|string',
        ]);

        if ($loan->status !== 'active') {
            return redirect()
                ->route('admin.loans.show', $loan)
                ->with('error', 'Hanya pinjaman aktif yang dapat dibayar');
        }

        $remainingBalance = $loan->remaining_balance;
        if ($validated['amount'] > $remainingBalance) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Pembayaran melebihi sisa pinjaman');
        }

        // Create payment transaction
        Transaction::create([
            'transaction_number' => 'TRX' . date('YmdHis') . rand(100, 999),
            'type' => 'income',
            'category' => 'loan_payment',
            'amount' => $validated['amount'],
            'description' => "Pembayaran pinjaman {$loan->member->full_name}",
            'transaction_date' => Carbon::today(),
            'member_id' => $loan->member_id,
            'savings_loan_id' => $loan->id,
            'recorded_by' => auth()->id(),
            'payment_method' => $validated['payment_method'],
            'notes' => $validated['notes'],
        ]);

        // Check if loan is fully paid
        if (($remainingBalance - $validated['amount']) <= 0) {
            $loan->update(['status' => 'completed']);
        }

        return redirect()
            ->route('admin.loans.show', $loan)
            ->with('success', 'Pembayaran berhasil dicatat');
    }

    private function getUserMaxApprovalAmount()
    {
        $user = auth()->user();
        
        // Check user role and return max approval amount
        // This is a temporary implementation until proper role system is integrated
        $userRole = $user->roles->first()?->slug ?? 'staff';
        
        if ($userRole === 'super-admin' || $userRole === 'ketua-koperasi') {
            return 999999999; // Unlimited
        } elseif ($userRole === 'manager-keuangan') {
            return 10000000; // 10 juta
        } else {
            return 5000000; // 5 juta
        }
    }

    public function report()
    {
        $monthlyLoans = SavingsLoan::where('type', 'loan')
            ->where('status', '!=', 'rejected')
            ->selectRaw('MONTH(created_at) as month, YEAR(created_at) as year, COUNT(*) as count, SUM(amount) as total')
            ->groupBy('year', 'month')
            ->orderBy('year', 'desc')
            ->orderBy('month', 'desc')
            ->limit(12)
            ->get();

        $loanStatus = SavingsLoan::where('type', 'loan')
            ->selectRaw('status, COUNT(*) as count, SUM(amount) as total')
            ->groupBy('status')
            ->get();

        $overdueLoans = SavingsLoan::where('type', 'loan')
            ->where('status', 'active')
            ->where('due_date', '<', Carbon::today())
            ->with('member')
            ->get();

        return view('admin.loans.report', compact('monthlyLoans', 'loanStatus', 'overdueLoans'));
    }
}
