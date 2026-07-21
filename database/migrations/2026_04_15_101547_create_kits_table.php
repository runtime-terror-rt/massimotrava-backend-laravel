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
        Schema::create('kits', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('user_subscription_id')->nullable()->constrained('user_subscriptions')->nullOnDelete();
            $table->string('activation_code')->unique();
            $table->string('inv_code')->unique()->nullable();
            $table->enum('status', [
                'requested',
                'processing',
                'shipped',
                'delivered',
                'activated',
                'pickup_scheduled',
                'pickup_assigned',
                'sample_collected',
                'received_at_lab',
                'processing_at_lab',
                'results_ready',
                'completed',
                'cancelled'
            ])->default('requested');
            $table->foreignId('added_by_admin_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('courier_name')->nullable();
            $table->string('tracking_number')->nullable();
            $table->text('admin_notes')->nullable();
            $table->timestamp('shipped_at')->nullable();
            $table->timestamp('delivered_at')->nullable();
            $table->timestamp('activated_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kits');
    }
};
