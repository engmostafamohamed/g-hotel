<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('loyalty_transactions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('balance_after');
            $table->bigInteger('points_change');   // + for credit, - for debit
            $table->enum('type',['earn','redeem','adjust_depit','adjust_credit','expire']);
            $table->string('source')->nullable();  // e.g., 'order', 'manual', 'promo2025'
            $table->string('source_id')->nullable(); // e.g., order id, idempotency key
            $table->json('meta')->nullable();
            $table->foreignId('account_id')->constrained('loyalty_accounts')->cascadeOnDelete();
            $table->timestamp('valid_from')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->index('account_id','type');
            $table->index('source','source_id');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('loyalty_transactions');
    }
};
