<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SavingsLoan extends Model
{
    use HasFactory;

    protected $fillable = [
        'member_id',
        'type',
        'transaction_number',
        'amount',
        'interest_rate',
        'tenure_months',
        'monthly_installment',
        'due_date',
        'status',
        'purpose',
        'notes',
        'approved_by',
        'approved_at',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'interest_rate' => 'decimal:2',
        'monthly_installment' => 'decimal:2',
        'due_date' => 'date',
        'approved_at' => 'datetime',
    ];

    public function member()
    {
        return $this->belongsTo(Member::class);
    }

    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    public function getRemainingBalanceAttribute()
    {
        if ($this->type === 'loan') {
            $paid = $this->transactions()
                ->where('category', 'loan_payment')
                ->sum('amount');
            return $this->amount - $paid;
        }
        return 0;
    }

    public function getIsOverdueAttribute()
    {
        return $this->type === 'loan' 
            && $this->due_date 
            && $this->due_date->isPast() 
            && $this->status === 'active';
    }
}
