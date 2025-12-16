<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Member;
use App\Models\SavingsLoan;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\PDF;

class MemberController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:members.view')->only(['index', 'show', 'print', 'pdf']);
        $this->middleware('permission:members.create')->only(['create', 'store', 'register']);
        $this->middleware('permission:members.edit')->only(['edit', 'update']);
        $this->middleware('permission:members.delete')->only(['destroy']);
    }
    public function index(Request $request)
    {
        $query = Member::with(['savingsLoans' => function($q) {
            $q->where('type', 'loan')->whereIn('status', ['active', 'overdue']);
        }]);

        // Search
        if ($request->search) {
            $query->where(function($q) use ($request) {
                $q->where('full_name', 'like', '%' . $request->search . '%')
                  ->orWhere('member_number', 'like', '%' . $request->search . '%')
                  ->orWhere('nik', 'like', '%' . $request->search . '%');
            });
        }

        // Filter by sector
        if ($request->business_sector) {
            $query->where('business_sector', $request->business_sector);
        }

        // Filter by status
        if ($request->status) {
            $query->where('status', $request->status);
        }

        $members = $query->orderBy('created_at', 'desc')->paginate(15);

        return view('admin.members.index', compact('members'));
    }

    public function create()
    {
        return view('admin.members.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'full_name' => 'required|string|max:255',
            'nik' => 'required|string|unique:members,nik',
            'email' => 'nullable|email|unique:members,email',
            'phone' => 'required|string|max:20',
            'address' => 'required|string',
            'business_sector' => 'required|in:pertanian,peternakan,perikanan,umkm',
            'experience' => 'required|in:baru,2-5_tahun,5+_tahun',
            'loan_limit' => 'nullable|numeric|min:0',
        ]);

        // Generate member number
        $lastMember = Member::orderBy('id', 'desc')->first();
        $memberNumber = 'KM' . str_pad(($lastMember ? $lastMember->id + 1 : 1), 6, '0', STR_PAD_LEFT);

        $validated['member_number'] = $memberNumber;
        $validated['join_date'] = Carbon::today();
        $validated['status'] = 'active';
        $validated['savings_balance'] = 0;
        $validated['loan_limit'] = $validated['loan_limit'] ?? 5000000; // Default 5 juta
        $validated['verification_data'] = [
            'id_card' => $request->has('id_card_document'),
            'address_proof' => $request->has('address_proof_document'),
            'business_proof' => $request->has('business_proof_document'),
        ];

        $member = Member::create($validated);

        return redirect()
            ->route('admin.members.show', $member)
            ->with('success', 'Anggota berhasil didaftarkan dengan nomor: ' . $member->member_number);
    }

    public function show(Member $member)
    {
        $member->load([
            'savingsLoans' => function($q) {
                $q->orderBy('created_at', 'desc');
            },
            'transactions' => function($q) {
                $q->orderBy('transaction_date', 'desc')->limit(10);
            }
        ]);

        $activeLoans = $member->activeLoans;
        $totalSavings = $member->totalSavings;
        $recentTransactions = $member->transactions->take(10);

        return view('admin.members.show', compact(
            'member',
            'activeLoans',
            'totalSavings',
            'recentTransactions'
        ));
    }

    public function edit(Member $member)
    {
        return view('admin.members.edit', compact('member'));
    }

    public function update(Request $request, Member $member)
    {
        $validated = $request->validate([
            'full_name' => 'required|string|max:255',
            'nik' => 'required|string|unique:members,nik,' . $member->id,
            'email' => 'nullable|email|unique:members,email,' . $member->id,
            'phone' => 'required|string|max:20',
            'address' => 'required|string',
            'business_sector' => 'required|in:pertanian,peternakan,perikanan,umkm',
            'experience' => 'required|in:baru,2-5_tahun,5+_tahun',
            'status' => 'required|in:active,inactive,suspended',
            'loan_limit' => 'nullable|numeric|min:0',
        ]);

        $member->update($validated);

        return redirect()
            ->route('admin.members.show', $member)
            ->with('success', 'Data anggota berhasil diperbarui');
    }

    public function destroy(Member $member)
    {
        // Check if member has active loans
        $activeLoans = $member->activeLoans;
        if ($activeLoans && $activeLoans->count() > 0) {
            return redirect()
                ->route('admin.members.show', $member)
                ->with('error', 'Tidak dapat menghapus anggota yang masih memiliki pinjaman aktif');
        }

        $member->delete();

        return redirect()
            ->route('admin.members.index')
            ->with('success', 'Anggota berhasil dihapus');
    }

    public function register()
    {
        return view('admin.members.register');
    }

    public function verify(Member $member)
    {
        $member->update([
            'verified_at' => Carbon::now(),
        ]);

        return redirect()
            ->route('admin.members.show', $member)
            ->with('success', 'Anggota berhasil diverifikasi');
    }

    public function updateStatus(Request $request, Member $member)
    {
        $validated = $request->validate([
            'status' => 'required|in:active,inactive,suspended',
            'notes' => 'nullable|string',
        ]);

        $member->update([
            'status' => $validated['status'],
        ]);

        // Add notes to verification data if provided
        if ($validated['notes']) {
            $verificationData = $member->verification_data ?? [];
            $verificationData['status_notes'] = $validated['notes'];
            $member->update(['verification_data' => $verificationData]);
        }

        return redirect()
            ->route('admin.members.show', $member)
            ->with('success', 'Status anggota berhasil diperbarui');
    }

    public function print()
    {
        $members = Member::query()
            ->when(request('status'), function ($query, $status) {
                return $query->where('status', $status);
            })
            ->when(request('search'), function ($query, $search) {
                return $query->where(function ($q) use ($search) {
                    $q->where('full_name', 'like', "%{$search}%")
                      ->orWhere('member_number', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%");
                });
            })
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.members.print', compact('members'));
    }

    public function pdf()
    {
        $members = Member::query()
            ->when(request('status'), function ($query, $status) {
                return $query->where('status', $status);
            })
            ->when(request('search'), function ($query, $search) {
                return $query->where(function ($q) use ($search) {
                    $q->where('full_name', 'like', "%{$search}%")
                      ->orWhere('member_number', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%");
                });
            })
            ->orderBy('created_at', 'desc')
            ->get();

        $pdf = PDF::loadView('admin.members.pdf', compact('members'));
        return $pdf->download('members-' . date('Y-m-d') . '.pdf');
    }
}
