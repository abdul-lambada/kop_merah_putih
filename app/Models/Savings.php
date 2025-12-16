<?php

namespace App\Models;

class Savings extends SavingsLoan
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'savings_loans';

    /**
     * Scope a query to only include savings records.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeSavings($query)
    {
        return $query->where('type', 'savings');
    }

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('savings', function ($builder) {
            $builder->where('type', 'savings');
        });
    }
}
