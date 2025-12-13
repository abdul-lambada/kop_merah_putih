<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Member;
use App\Models\SavingsLoan;
use App\Models\BusinessUnit;
use App\Models\Transaction;
use App\Models\FinancialReport;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:dashboard.view');
    }
    public function index()
    {
        /** @var User $user */
        $user = auth()->user();
        
        // Base data for all roles
        $today = Carbon::today();
        $thisMonth = Carbon::now()->startOfMonth();
        
        // Role-based dashboard data
        $data = [];
        
        if ($user->hasRole('Super Admin')) {
            $data = $this->getSuperAdminData($today, $thisMonth);
        } elseif ($user->hasRole('Ketua Koperasi')) {
            $data = $this->getKetuaKoperasiData($today, $thisMonth);
        } elseif ($user->hasRole('Manager Keuangan')) {
            $data = $this->getManagerKeuanganData($today, $thisMonth);
        } elseif ($user->hasRole('Manager Unit')) {
            $data = $this->getManagerUnitData($today, $thisMonth);
        } elseif ($user->hasRole('Staff Administrasi')) {
            $data = $this->getStaffAdminData($today, $thisMonth);
        } elseif ($user->hasRole('Bendahara Unit')) {
            $data = $this->getBendaharaUnitData($today, $thisMonth);
        } elseif ($user->hasRole('Anggota')) {
            $data = $this->getAnggotaData($today, $thisMonth);
        }
        
        // Debug: Check if monthlyChart exists in data
        if (!isset($data['monthlyChart'])) {
            $data['monthlyChart'] = [
                'labels' => ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
                'data' => [65000000, 72000000, 68000000, 81000000, 89000000, 95000000],
            ];
        }
        
        $monthlyChart = $data['monthlyChart'];
        
        // Determine priority role
        $priorityRole = $this->getPriorityRole($user);
        
        return view('admin.dashboard.index', compact('data', 'monthlyChart', 'priorityRole'));
    }
    
    private function getPriorityRole($user)
    {
        $roles = ['Super Admin', 'Ketua Koperasi', 'Manager Keuangan', 'Manager Unit', 'Staff Administrasi', 'Bendahara Unit'];
        
        foreach ($roles as $role) {
            if ($user->roles->contains('name', $role)) {
                return $role;
            }
        }
        
        return 'Anggota';
    }
    
    private function getSuperAdminData($today, $thisMonth)
    {
        return [
            'overview' => [
                'total_users' => \App\Models\User::count(),
                'total_members' => Member::where('status', 'active')->count(),
                'total_units' => BusinessUnit::count(),
                'system_health' => 'good',
            ],
            'financial' => [
                'total_savings' => SavingsLoan::where('type', 'savings')->where('status', 'completed')->sum('amount'),
                'total_loans' => SavingsLoan::where('type', 'loan')->whereIn('status', ['active', 'completed'])->sum('amount'),
                'monthly_revenue' => Transaction::whereMonth('created_at', $thisMonth)->sum('amount'),
            ],
            'loans' => [
                'loan_portfolio' => SavingsLoan::where('type', 'loan')->where('status', 'active')->sum('amount'),
                'overdue_loans' => SavingsLoan::where('type', 'loan')->where('due_date', '<', $today)->where('status', 'active')->count(),
            ],
            'units' => [
                'active_units' => BusinessUnit::where('status', 'active')->count(),
            ],
            'transactions' => [
                'monthly_revenue' => Transaction::whereMonth('created_at', $thisMonth)->sum('amount'),
            ],
            'recent_activities' => Transaction::latest()->take(5)->get(),
            'pendingLoans' => SavingsLoan::where('type', 'loan')->where('status', 'pending')->get(),
            'sectorDistribution' => Member::selectRaw('business_sector, COUNT(*) as count')
                ->whereNotNull('business_sector')
                ->groupBy('business_sector')
                ->get(),
            'unitPerformance' => BusinessUnit::select('name', 'current_balance as monthly_revenue')
                ->where('status', 'active')
                ->orderBy('current_balance', 'desc')
                ->get(),
            'pendingLoans' => SavingsLoan::where('type', 'loan')->where('status', 'pending')->get(),
            'sectorDistribution' => Member::selectRaw('business_sector, COUNT(*) as count')
                ->whereNotNull('business_sector')
                ->groupBy('business_sector')
                ->get(),
            'unitPerformance' => BusinessUnit::select('name', 'current_balance as monthly_revenue')
                ->where('status', 'active')
                ->orderBy('current_balance', 'desc')
                ->get(),
            'monthlyChart' => [
                'labels' => ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
                'data' => [65000000, 72000000, 68000000, 81000000, 89000000, 95000000],
            ],
        ];
    }
    
    private function getKetuaKoperasiData($today, $thisMonth)
    {
        return [
            'overview' => [
                'total_members' => Member::where('status', 'active')->count(),
                'new_members_this_month' => Member::whereMonth('join_date', $thisMonth)->count(),
                'total_units' => BusinessUnit::count(),
            ],
            'financial' => [
                'total_savings' => SavingsLoan::where('type', 'savings')->where('status', 'completed')->sum('amount'),
                'total_loans' => SavingsLoan::where('type', 'loan')->where('status', 'active')->sum('amount'),
                'monthly_growth' => $this->calculateMonthlyGrowth(),
            ],
            'pending_approvals' => [
                'large_loans' => SavingsLoan::where('type', 'loan')->where('amount', '>', 50000000)->where('status', 'pending')->count(),
                'new_members' => Member::where('status', 'pending')->count(),
            ],
            'recent_activities' => Transaction::latest()->take(5)->get(),
            'monthlyChart' => [
                'labels' => ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
                'data' => [65000000, 72000000, 68000000, 81000000, 89000000, 95000000],
            ],
        ];
    }
    
    private function getManagerKeuanganData($today, $thisMonth)
    {
        return [
            'savings' => [
                'total_savings' => SavingsLoan::where('type', 'savings')->where('status', 'completed')->sum('amount'),
                'new_savings_this_month' => SavingsLoan::where('type', 'savings')->whereMonth('created_at', $thisMonth)->sum('amount'),
                'savings_growth' => $this->calculateSavingsGrowth(),
            ],
            'loans' => [
                'active_loans' => SavingsLoan::where('type', 'loan')->where('status', 'active')->count(),
                'overdue_loans' => SavingsLoan::where('type', 'loan')->where('due_date', '<', $today)->where('status', 'active')->count(),
                'loan_portfolio' => SavingsLoan::where('type', 'loan')->where('status', 'active')->sum('amount'),
            ],
            'transactions' => [
                'daily_transactions' => Transaction::whereDate('created_at', $today)->count(),
                'monthly_total' => Transaction::whereMonth('created_at', $thisMonth)->sum('amount'),
            ],
            'recent_activities' => Transaction::latest()->take(5)->get(),
            'monthlyChart' => [
                'labels' => ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
                'data' => [65000000, 72000000, 68000000, 81000000, 89000000, 95000000],
            ],
        ];
    }
    
    private function getManagerUnitData($today, $thisMonth)
    {
        return [
            'units' => [
                'total_units' => BusinessUnit::count(),
                'active_units' => BusinessUnit::where('status', 'active')->count(),
                'unit_performance' => $this->getUnitPerformance(),
            ],
            'transactions' => [
                'today_transactions' => Transaction::whereDate('created_at', $today)->count(),
                'monthly_revenue' => Transaction::whereMonth('created_at', $thisMonth)->sum('amount'),
            ],
            'staff' => [
                'total_staff' => \App\Models\User::role('Manager Unit')->count(),
                'active_staff' => \App\Models\User::role('Manager Unit')->where('status', 'active')->count(),
            ],
            'recent_activities' => Transaction::latest()->take(5)->get(),
            'monthlyChart' => [
                'labels' => ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
                'data' => [65000000, 72000000, 68000000, 81000000, 89000000, 95000000],
            ],
        ];
    }
    
    private function getStaffAdminData($today, $thisMonth)
    {
        return [
            'members' => [
                'total_members' => Member::where('status', 'active')->count(),
                'new_members_today' => Member::whereDate('created_at', $today)->count(),
                'pending_verifications' => Member::where('status', 'pending')->count(),
            ],
            'tasks' => [
                'registrations_today' => Member::whereDate('created_at', $today)->count(),
                'applications_pending' => SavingsLoan::where('status', 'pending')->count(),
            ],
            'recent_activities' => Transaction::latest()->take(5)->get(),
            'monthlyChart' => [
                'labels' => ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
                'data' => [65000000, 72000000, 68000000, 81000000, 89000000, 95000000],
            ],
        ];
    }
    
    private function getBendaharaUnitData($today, $thisMonth)
    {
        return [
            'cash' => [
                'today_cash' => Transaction::whereDate('created_at', $today)->sum('amount'),
                'monthly_cash' => Transaction::whereMonth('created_at', $thisMonth)->sum('amount'),
                'cash_balance' => $this->getUnitCashBalance(),
            ],
            'transactions' => [
                'recent_transactions' => Transaction::whereDate('created_at', $today)->latest()->take(10)->get(),
                'pending_transactions' => Transaction::where('status', 'pending')->count(),
            ],
            'recent_activities' => Transaction::latest()->take(5)->get(),
            'monthlyChart' => [
                'labels' => ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
                'data' => [65000000, 72000000, 68000000, 81000000, 89000000, 95000000],
            ],
        ];
    }
    
    private function getAnggotaData($today, $thisMonth)
    {
        $user = auth()->user();
        $member = Member::where('email', $user->email)->first();
        
        return [
            'profile' => [
                'member_info' => $member,
                'membership_status' => $member->status ?? 'inactive',
            ],
            'savings' => [
                'total_savings' => SavingsLoan::where('member_id', $member->id ?? 0)->where('type', 'savings')->where('status', 'completed')->sum('amount'),
                'savings_this_month' => SavingsLoan::where('member_id', $member->id ?? 0)->where('type', 'savings')->whereMonth('created_at', $thisMonth)->sum('amount'),
            ],
            'loans' => [
                'active_loans' => SavingsLoan::where('member_id', $member->id ?? 0)->where('type', 'loan')->where('status', 'active')->get(),
                'loan_history' => SavingsLoan::where('member_id', $member->id ?? 0)->where('type', 'loan')->whereIn('status', ['completed', 'rejected'])->count(),
            ],
            'transactions' => [
                'recent_transactions' => Transaction::where('member_id', $member->id ?? 0)->latest()->take(5)->get(),
            ],
            'monthlyChart' => [
                'labels' => ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
                'data' => [65000000, 72000000, 68000000, 81000000, 89000000, 95000000],
            ],
        ];
    }
    
    private function calculateMonthlyGrowth()
    {
        return 0; // Placeholder implementation
    }
    
    private function calculateSavingsGrowth()
    {
        return 0; // Placeholder implementation
    }
    
    private function getUnitPerformance()
    {
        return BusinessUnit::all(); // Placeholder implementation
    }
    
    private function getUnitCashBalance()
    {
        return 0; // Placeholder implementation
    }
}
