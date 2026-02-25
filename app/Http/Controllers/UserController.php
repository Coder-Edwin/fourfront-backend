<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * CREATE a new user account.
     * No authentication required — per assessment brief.
     *
     * POST /api/users
     *
     * Expected body:
     * {
     *   "name": "Edwin",
     *   "email": "edwin@example.com",
     *   "phone": "0712345678"   (optional)
     * }
     */
    public function store(Request $request)
    {
        // Validate incoming request fields
        $validated = $request->validate([
            'name'  => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone' => 'nullable|string|max:20',
        ]);

        // Create and save the user
        $user = User::create($validated);

        return response()->json([
            'message' => 'User created successfully.',
            'user'    => $user,
        ], 201);
    }

    /**
     * VIEW a user's profile.
     * Returns all wallets, each wallet's balance,
     * and the user's total balance across all wallets.
     *
     * GET /api/users/{id}
     */
    public function show(User $user)
    {
        // Load all wallets belonging to this user
        // We also load each wallet's transactions so balance can be calculated
        $wallets = $user->wallets->map(function ($wallet) {
            return [
                'id'       => $wallet->id,
                'name'     => $wallet->name,
                'currency' => $wallet->currency,
                'balance'  => $wallet->balance,  // calculated via getBalanceAttribute()
            ];
        });

        return response()->json([
            'user' => [
                'id'            => $user->id,
                'name'          => $user->name,
                'email'         => $user->email,
                'phone'         => $user->phone,
            ],
            'wallets'       => $wallets,
            'total_balance' => $user->total_balance, // sum of all wallet balances
        ]);
    }
}