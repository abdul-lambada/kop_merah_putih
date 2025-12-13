<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SavingsLoan;

class SavingsLoanController extends Controller
{
    public function index()
    {
        $savingsLoans = SavingsLoan::with(['member', 'approvedBy'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('admin.savings-loans.index', compact('savingsLoans'));
    }

    public function show(SavingsLoan $savingsLoan)
    {
        $savingsLoan->load(['member', 'approvedBy', 'transactions']);

        return view('admin.savings-loans.show', compact('savingsLoan'));
    }
}
