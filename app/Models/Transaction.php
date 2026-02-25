<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'wallet_id',
        'type',
        'amount',
        'description',
    ];

    /**
     * A transaction belongs to one wallet.
     */
    public function wallet()
    {
        return $this->belongsTo(Wallet::class);
    }
}