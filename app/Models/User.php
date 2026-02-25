<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    /**
     * The attributes that are mass assignable.
     * These fields can be filled using User::create([...])
     */
    protected $fillable = [
        'name',
        'email',
        'phone',
    ];

    /**
     * A user can own multiple wallets.
     * e.g. one for personal use, one for business.
     */
    public function wallets()
    {
        return $this->hasMany(Wallet::class);
    }

    /**
     * Calculate the user's total balance across ALL their wallets.
     * Used when viewing the user profile.
     */
    public function getTotalBalanceAttribute()
    {
        return $this->wallets->sum(function ($wallet) {
            return $wallet->balance;
        });
    }
}