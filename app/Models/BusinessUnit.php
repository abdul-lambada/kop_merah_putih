<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BusinessUnit extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'type',
        'description',
        'address',
        'phone',
        'manager_name',
        'status',
        'initial_capital',
        'current_balance',
        'operating_hours',
        'notes',
    ];

    protected $casts = [
        'initial_capital' => 'decimal:2',
        'current_balance' => 'decimal:2',
        'operating_hours' => 'array',
    ];

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    public function getRevenueAttribute()
    {
        return $this->transactions()
            ->where('type', 'income')
            ->sum('amount');
    }

    public function getExpensesAttribute()
    {
        return $this->transactions()
            ->where('type', 'expense')
            ->sum('amount');
    }

    public function getProfitAttribute()
    {
        return $this->revenue - $this->expenses;
    }

    public function getMonthlyRevenueAttribute()
    {
        return $this->transactions()
            ->where('type', 'income')
            ->whereMonth('transaction_date', now()->month)
            ->sum('amount');
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }
}
