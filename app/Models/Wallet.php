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
     * A wallet belongs to one user.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * A wallet has many transactions.
     */
    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    /**
     * Calculate this wallet's balance.
     * Income adds to balance, expense subtracts from balance.
     */
    public function balance(): float
    {
        $income  = $this->transactions()
                        ->where('type', 'income')
                        ->sum('amount');

        $expense = $this->transactions()
                        ->where('type', 'expense')
                        ->sum('amount');

        return (float) ($income - $expense);
    }
}