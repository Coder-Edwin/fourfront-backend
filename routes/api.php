<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WalletController;
use App\Http\Controllers\TransactionController;

/*
|--------------------------------------------------------------------------
| API Routes — Fourfront Money Tracker
|--------------------------------------------------------------------------
|
| All routes here are prefixed with /api automatically by Laravel.
|
| Route summary:
|   POST   /api/users                          → Create a new user
|   GET    /api/users/{user}                   → View user profile + all wallets + total balance
|   POST   /api/users/{user}/wallets           → Create a wallet for a user
|   GET    /api/wallets/{wallet}               → View wallet balance + all transactions
|   POST   /api/wallets/{wallet}/transactions  → Add a transaction to a wallet
|
*/

// ── USER ROUTES ──────────────────────────────────────────────────────────────

/**
 * Create a new user account.
 * No authentication required.
 */
Route::post('/users', [UserController::class, 'store']);

/**
 * View a user's full profile.
 * Includes all wallets, each wallet's balance, and total balance.
 */
Route::get('/users/{user}', [UserController::class, 'show']);

// ── WALLET ROUTES ─────────────────────────────────────────────────────────────

/**
 * Create a new wallet for a specific user.
 * A user can have multiple wallets.
 */
Route::post('/users/{user}/wallets', [WalletController::class, 'store']);

/**
 * View a specific wallet.
 * Includes the wallet balance and all its transactions.
 */
Route::get('/wallets/{wallet}', [WalletController::class, 'show']);

// ── TRANSACTION ROUTES ────────────────────────────────────────────────────────

/**
 * Add a transaction (income or expense) to a specific wallet.
 * Income adds to balance, expense subtracts from balance.
 */
Route::post('/wallets/{wallet}/transactions', [TransactionController::class, 'store']);