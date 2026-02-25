<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    /**
     * The attributes that are mass assignable.
     * These fields can be filled via User::create([...])
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
     * Calculate the total balance across ALL of the user's wallets.
     * Used when viewing a user's profile.
     */
    public function totalBalance(): float
    {
        return $this->wallets->sum(function ($wallet) {
            return $wallet->balance();
        });
    }
}