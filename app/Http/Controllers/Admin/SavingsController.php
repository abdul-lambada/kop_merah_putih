<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SavingsLoan;
use App\Models\Member;
use App\Models\Transaction;
use App\Models\BusinessUnit;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\PDF;

class SavingsController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:savings.view')->only(['index', 'show']);
        $this->middleware('permission:savings.create')->only(['create', 'store']);
        $this->middleware('permission:savings.edit')->only(['edit', 'update']);
        $this->middleware('permission:savings.approve')->only(['approve', 'reject']);
        $this->middleware('permission:savings.withdraw')->only(['withdraw']);
    }

    public function index(Request $request)
    {
        $query = SavingsLoan::with('member')
            ->where('type', 'savings');

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

        $savings = $query->orderBy('created_at', 'desc')->paginate(15);

        // Statistics
        $stats = [
            'total_savings' => SavingsLoan::where('type', 'savings')
                ->where('status', 'completed')->sum('amount'),
            'this_month_savings' => SavingsLoan::where('type', 'savings')
                ->where('status', 'completed')
                ->whereMonth('created_at', Carbon::now()->month)
                ->sum('amount'),
            'monthly_savings' => SavingsLoan::where('type', 'savings')
                ->where('status', 'completed')
                ->whereMonth('created_at', Carbon::now()->month)
                ->sum('amount'),
            'monthly_count' => SavingsLoan::where('type', 'savings')
                ->where('status', 'completed')
                ->whereMonth('created_at', Carbon::now()->month)
                ->count(),
            'pending_savings' => SavingsLoan::where('type', 'savings')
                ->where('status', 'pending')->count(),
            'pending_count' => SavingsLoan::where('type', 'savings')
                ->where('status', 'pending')->count(),
            'pending_amount' => SavingsLoan::where('type', 'savings')
                ->where('status', 'pending')->sum('amount'),
            'active_members_with_savings' => SavingsLoan::where('type', 'savings')
                ->where('status', 'completed')
                ->distinct('member_id')
                ->count(),
            'active_members' => SavingsLoan::where('type', 'savings')
                ->where('status', 'completed')
                ->distinct('member_id')
                ->count(),
            'total_count' => SavingsLoan::where('type', 'savings')->count(),
        ];

        return view('admin.savings.index', compact('savings', 'stats'));
    }

    public function create()
    {
        $members = Member::where('status', 'active')
            ->orderBy('full_name')
            ->get();

        $businessUnits = BusinessUnit::where('status', 'active')
            ->orderBy('name')
            ->get();

        return view('admin.savings.create', compact('members', 'businessUnits'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'member_id' => 'required|exists:members,id',
            'amount' => 'required|numeric|min:10000',
            'notes' => 'nullable|string',
        ]);

        $member = Member::findOrFail($validated['member_id']);
        
        // Generate transaction number
        $transactionNumber = 'SAV' . date('Ymd') . str_pad(SavingsLoan::count() + 1, 4, '0', STR_PAD_LEFT);

        $savings = SavingsLoan::create([
            'member_id' => $validated['member_id'],
            'type' => 'savings',
            'transaction_number' => $transactionNumber,
            'amount' => $validated['amount'],
            'status' => 'completed',
            'purpose' => 'Simpanan anggota',
            'notes' => $validated['notes'],
            'approved_by' => auth()->id(),
            'approved_at' => Carbon::now(),
        ]);

        // Update member savings balance
        $member->increment('savings_balance', $validated['amount']);

        // Create transaction record
        Transaction::create([
            'transaction_number' => 'TRX' . date('YmdHis') . rand(100, 999),
            'type' => 'income',
            'category' => 'savings_deposit',
            'amount' => $validated['amount'],
            'description' => "Simpanan dari {$member->full_name}",
            'transaction_date' => Carbon::today(),
            'member_id' => $member->id,
            'savings_loan_id' => $savings->id,
            'recorded_by' => auth()->id(),
            'payment_method' => 'cash',
        ]);

        return redirect()
            ->route('admin.savings.show', $savings)
            ->with('success', 'Simpanan berhasil dicatat');
    }

    public function show(SavingsLoan $saving)
    {
        $saving->load(['member', 'transactions', 'approvedBy']);

        return view('admin.savings.show', compact('saving'));
    }

    public function approve(SavingsLoan $saving)
    {
        if ($saving->status !== 'pending') {
            return redirect()
                ->route('admin.savings.show', $saving)
                ->with('error', 'Hanya simpanan dengan status pending yang dapat disetujui');
        }

        $saving->update([
            'status' => 'completed',
            'approved_by' => auth()->id(),
            'approved_at' => Carbon::now(),
        ]);

        // Update member savings balance
        $saving->member->increment('savings_balance', $saving->amount);

        // Create transaction record
        Transaction::create([
            'transaction_number' => 'TRX' . date('YmdHis') . rand(100, 999),
            'type' => 'income',
            'category' => 'savings_deposit',
            'amount' => $saving->amount,
            'description' => "Simpanan dari {$saving->member->full_name}",
            'transaction_date' => Carbon::today(),
            'member_id' => $saving->member_id,
            'savings_loan_id' => $saving->id,
            'recorded_by' => auth()->id(),
            'payment_method' => 'cash',
        ]);

        return redirect()
            ->route('admin.savings.show', $saving)
            ->with('success', 'Simpanan berhasil disetujui');
    }

    public function withdraw(Request $request, SavingsLoan $saving)
    {
        $validated = $request->validate([
            'amount' => 'required|numeric|min:10000|max:' . $saving->member->savings_balance,
            'notes' => 'nullable|string',
        ]);

        // Create withdrawal transaction
        $withdrawal = SavingsLoan::create([
            'member_id' => $saving->member_id,
            'type' => 'savings',
            'transaction_number' => 'WDR' . date('Ymd') . str_pad(SavingsLoan::count() + 1, 4, '0', STR_PAD_LEFT),
            'amount' => $validated['amount'],
            'status' => 'completed',
            'purpose' => 'Penarikan simpanan',
            'notes' => $validated['notes'],
            'approved_by' => auth()->id(),
            'approved_at' => Carbon::now(),
        ]);

        // Update member savings balance
        $saving->member->decrement('savings_balance', $validated['amount']);

        // Create transaction record
        Transaction::create([
            'transaction_number' => 'TRX' . date('YmdHis') . rand(100, 999),
            'type' => 'expense',
            'category' => 'savings_withdrawal',
            'amount' => $validated['amount'],
            'description' => "Penarikan simpanan oleh {$saving->member->full_name}",
            'transaction_date' => Carbon::today(),
            'member_id' => $saving->member_id,
            'savings_loan_id' => $withdrawal->id,
            'recorded_by' => auth()->id(),
            'payment_method' => 'cash',
        ]);

        return redirect()
            ->route('admin.savings.show', $saving)
            ->with('success', 'Penarikan simpanan berhasil');
    }

    public function report()
    {
        $monthlySavings = SavingsLoan::where('type', 'savings')
            ->where('status', 'completed')
            ->selectRaw('MONTH(created_at) as month, YEAR(created_at) as year, SUM(amount) as total')
            ->groupBy('year', 'month')
            ->orderBy('year', 'desc')
            ->orderBy('month', 'desc')
            ->limit(12)
            ->get();

        $topSavers = Member::with(['savingsLoans' => function($q) {
                $q->where('type', 'savings')->where('status', 'completed');
            }])
            ->where('status', 'active')
            ->get()
            ->map(function($member) {
                return [
                    'member' => $member,
                    'total_savings' => $member->savingsLoans->sum('amount'),
                ];
            })
            ->sortByDesc('total_savings')
            ->take(10);

        return view('admin.savings.report', compact('monthlySavings', 'topSavers'));
    }

    public function print(Request $request)
    {
        $query = SavingsLoan::with('member')
            ->where('type', 'savings');

        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('transaction_number', 'like', '%' . $request->search . '%')
                  ->orWhereHas('member', function ($memberQuery) use ($request) {
                      $memberQuery->where('full_name', 'like', '%' . $request->search . '%')
                                  ->orWhere('member_number', 'like', '%' . $request->search . '%');
                  });
            });
        }

        if ($request->status) {
            $query->where('status', $request->status);
        }

        if ($request->date_from) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->date_to) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $savings = $query->orderBy('created_at', 'desc')->get();

        return view('admin.savings.print', compact('savings'));
    }

    public function pdf(Request $request)
    {
        $query = SavingsLoan::with('member')
            ->where('type', 'savings');

        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('transaction_number', 'like', '%' . $request->search . '%')
                  ->orWhereHas('member', function ($memberQuery) use ($request) {
                      $memberQuery->where('full_name', 'like', '%' . $request->search . '%')
                                  ->orWhere('member_number', 'like', '%' . $request->search . '%');
                  });
            });
        }

        if ($request->status) {
            $query->where('status', $request->status);
        }

        if ($request->date_from) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->date_to) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $savings = $query->orderBy('created_at', 'desc')->get();

        $pdf = PDF::loadView('admin.savings.pdf', compact('savings'));

        return $pdf->download('savings-' . date('Y-m-d') . '.pdf');
    }
}
