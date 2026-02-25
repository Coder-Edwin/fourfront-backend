<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Each transaction belongs to a wallet.
     * Type is either 'income' (adds to balance) or 'expense' (subtracts).
     */
    public function up(): void
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            // Links transaction to its wallet — deletes if wallet is deleted
            $table->foreignId('wallet_id')->constrained()->onDelete('cascade');
            // Only 'income' or 'expense' allowed — enforced at DB and controller level
            $table->enum('type', ['income', 'expense']);
            // Positive amounts only — validation handled in TransactionController
            $table->decimal('amount', 15, 2);
            // Optional note about the transaction
            $table->string('description')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};