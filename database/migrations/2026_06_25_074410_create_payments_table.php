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
        if (!Schema::hasTable('payments')) {
            Schema::create('payments', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained()->onDelete('cascade');
                $table->foreignId('user_subscription_id')->nullable()->constrained('user_subscriptions')->onDelete('set null');
                $table->string('stripe_charge_id')->unique(); // Stripe Transaction/Charge ID (ch_xxxx or pi_xxxx)
                $table->decimal('amount', 10, 2);
                $table->string('currency', 10)->default('EUR');
                $table->string('payment_status')->default('succeeded'); // succeeded, failed, refunded
                $table->string('payment_method')->nullable(); // card, bank_transfer 
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
