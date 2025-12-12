<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FinancialReport extends Model
{
    use HasFactory;

    protected $fillable = [
        'report_number',
        'type',
        'title',
        'period_start',
        'period_end',
        'total_income',
        'total_expense',
        'net_profit',
        'total_savings',
        'total_loans',
        'loan_portfolio',
        'active_members_count',
        'unit_performance',
        'charts_data',
        'summary',
        'notes',
        'generated_by',
        'generated_at',
    ];

    protected $casts = [
        'period_start' => 'date',
        'period_end' => 'date',
        'total_income' => 'decimal:2',
        'total_expense' => 'decimal:2',
        'net_profit' => 'decimal:2',
        'total_savings' => 'decimal:2',
        'total_loans' => 'decimal:2',
        'loan_portfolio' => 'decimal:2',
        'unit_performance' => 'array',
        'charts_data' => 'array',
        'generated_at' => 'datetime',
    ];

    public function generatedBy()
    {
        return $this->belongsTo(User::class, 'generated_by');
    }

    public function getProfitMarginAttribute()
    {
        if ($this->total_income > 0) {
            return ($this->net_profit / $this->total_income) * 100;
        }
        return 0;
    }

    public function getLoanToSavingsRatioAttribute()
    {
        if ($this->total_savings > 0) {
            return ($this->total_loans / $this->total_savings) * 100;
        }
        return 0;
    }

    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }

    public function scopeByPeriod($query, $year, $month = null)
    {
        if ($month) {
            return $query->whereYear('period_start', $year)
                       ->whereMonth('period_start', $month);
        }
        return $query->whereYear('period_start', $year);
    }

    public function scopeLatest($query)
    {
        return $query->orderBy('period_end', 'desc');
    }
}
