<?php

namespace App\Http\Controllers;

use App\Models\Wallet;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    /**
     * ADD a transaction to a wallet.
     * Income adds to the balance, expense subtracts from it.
     *
     * POST /api/wallets/{wallet}/transactions
     *
     * Expected body:
     * {
     *   "type": "income",         // must be 'income' or 'expense'
     *   "amount": 5000.00,        // must be a positive number
     *   "description": "Salary"   // optional
     * }
     */
    public function store(Request $request, Wallet $wallet)
    {
        // Validate all fields strictly
        $validated = $request->validate([
            // type must be exactly 'income' or 'expense'
            'type'        => 'required|in:income,expense',
            // amount must be a positive number greater than zero
            'amount'      => 'required|numeric|min:0.01',
            'description' => 'nullable|string|max:255',
        ]);

        // Create the transaction linked to this wallet
        $transaction = $wallet->transactions()->create($validated);

        // Return the new balance after this transaction
        return response()->json([
            'message'         => 'Transaction added successfully.',
            'transaction'     => $transaction,
            'wallet_balance'  => $wallet->balance,  // updated balance
        ], 201);
    }
}