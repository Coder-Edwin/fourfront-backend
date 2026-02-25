<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Each wallet belongs to a user.
     * A user can have multiple wallets (e.g. personal, business).
     */
    public function up(): void
    {
        Schema::create('wallets', function (Blueprint $table) {
            $table->id();
            // Links wallet to its owner — deletes wallet if user is deleted
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('name');                    // e.g. "Business", "Personal"
            $table->string('currency')->default('KES'); // Kenyan Shilling by default
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wallets');
    }
};
