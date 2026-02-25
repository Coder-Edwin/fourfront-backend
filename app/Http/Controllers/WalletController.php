<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Wallet;
use Illuminate\Http\Request;

class WalletController extends Controller
{
    /**
     * CREATE a new wallet for a user.
     * A user can have multiple wallets.
     *
     * POST /api/users/{user}/wallets
     *
     * Expected body:
     * {
     *   "name": "Business Wallet",
     *   "currency": "KES"   (optional, defaults to KES)
     * }
     */
    public function store(Request $request, User $user)
    {
        // Validate incoming request fields
        $validated = $request->validate([
            'name'     => 'required|string|max:255',
            'currency' => 'nullable|string|max:10',
        ]);

        // Create wallet and link it to the user
        $wallet = $user->wallets()->create($validated);

        return response()->json([
            'message' => 'Wallet created successfully.',
            'wallet'  => $wallet,
        ], 201);
    }

    /**
     * VIEW a single wallet.
     * Returns the wallet's balance and all its transactions.
     *
     * GET /api/wallets/{wallet}
     */
    public function show(Wallet $wallet)
    {
        // Load all transactions for this wallet
        $transactions = $wallet->transactions()
                               ->latest()   // newest first
                               ->get();

        return response()->json([
            'wallet' => [
                'id'       => $wallet->id,
                'name'     => $wallet->name,
                'currency' => $wallet->currency,
                'balance'  => $wallet->balance,  // calculated via getBalanceAttribute()
            ],
            'transactions' => $transactions,
        ]);
    }
}