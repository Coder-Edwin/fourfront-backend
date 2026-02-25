<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Wallet extends Model
{
    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'user_id',
        'name',
        'currency',
    ];

    /**
     * Each wallet belongs to one user.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * A wallet can have many transactions.
     */
    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    /**
     * Calculate this wallet's balance dynamically.
     * Income adds to balance, expense subtracts from balance.
     * This is accessed as $wallet->balance (like a property).
     */
    public function getBalanceAttribute()
    {
        $income  = $this->transactions()
                        ->where('type', 'income')
                        ->sum('amount');

        $expense = $this->transactions()
                        ->where('type', 'expense')
                        ->sum('amount');

        return $income - $expense;
    }
}