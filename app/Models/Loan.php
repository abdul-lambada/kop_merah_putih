<?php

namespace App\Models;

class Loan extends SavingsLoan
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'savings_loans';

    /**
     * Scope a query to only include loan records.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeLoans($query)
    {
        return $query->where('type', 'loan');
    }

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('loans', function ($builder) {
            $builder->where('type', 'loan');
        });
    }
}
