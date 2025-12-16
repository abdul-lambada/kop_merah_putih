<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Member extends Model
{
    use HasFactory;

    protected $fillable = [
        'member_number',
        'nik',
        'full_name',
        'email',
        'phone',
        'address',
        'business_sector',
        'experience',
        'join_date',
        'status',
        'savings_balance',
        'loan_limit',
        'verification_data',
        'verified_at',
    ];

    protected $casts = [
        'join_date' => 'date',
        'verified_at' => 'datetime',
        'savings_balance' => 'decimal:2',
        'loan_limit' => 'decimal:2',
        'verification_data' => 'array',
    ];

    public function savingsLoans()
    {
        return $this->hasMany(SavingsLoan::class);
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    public function getActiveLoansAttribute()
    {
        return $this->savingsLoans()
            ->where('type', 'loan')
            ->whereIn('status', ['active', 'overdue'])
            ->get();
    }

    public function getTotalSavingsAttribute()
    {
        return $this->savingsLoans()
            ->where('type', 'savings')
            ->where('status', 'completed')
            ->sum('amount');
    }

    /**
     * Backwards-compatible helper used in views to get total savings.
     * Mirrors the logic from the total_savings accessor.
     */
    public function totalSavings()
    {
        return $this->total_savings;
    }

    /**
     * Helper used in views to get total active loan amount.
     */
    public function activeLoanAmount()
    {
        return $this->savingsLoans()
            ->where('type', 'loan')
            ->whereIn('status', ['active', 'overdue'])
            ->sum('amount');
    }
}
