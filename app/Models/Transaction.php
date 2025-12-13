<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'transaction_number',
        'type',
        'category',
        'amount',
        'description',
        'transaction_date',
        'member_id',
        'business_unit_id',
        'savings_loan_id',
        'recorded_by',
        'payment_method',
        'reference_number',
        'notes',
        'attachments',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'transaction_date' => 'date',
        'attachments' => 'array',
    ];

    public function member()
    {
        return $this->belongsTo(Member::class);
    }

    public function businessUnit()
    {
        return $this->belongsTo(BusinessUnit::class);
    }

    public function savingsLoan()
    {
        return $this->belongsTo(SavingsLoan::class);
    }

    public function recordedBy()
    {
        return $this->belongsTo(User::class, 'recorded_by');
    }

    public function scopeIncome($query)
    {
        return $query->where('type', 'income');
    }

    public function scopeExpense($query)
    {
        return $query->where('type', 'expense');
    }

    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    public function scopeDateRange($query, $start, $end)
    {
        return $query->whereBetween('transaction_date', [$start, $end]);
    }

    public function scopeMonthly($query, $month = null)
    {
        $month = $month ?? now()->month;
        return $query->whereMonth('transaction_date', $month);
    }

    /**
     * Check if transaction can be edited
     */
    public function canEdit()
    {
        // Can edit if transaction is from current month and not older than 7 days
        $transactionDate = \Carbon\Carbon::parse($this->transaction_date);
        $now = \Carbon\Carbon::now();
        
        // Allow editing if transaction is from current month
        if ($transactionDate->month === $now->month && $transactionDate->year === $now->year) {
            return true;
        }
        
        // Allow editing if transaction is less than 7 days old
        if ($transactionDate->diffInDays($now) <= 7) {
            return true;
        }
        
        return false;
    }
}
