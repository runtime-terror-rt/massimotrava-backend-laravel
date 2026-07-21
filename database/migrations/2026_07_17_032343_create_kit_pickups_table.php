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
        Schema::create('kit_pickups', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kit_id')->constrained()->onDelete('cascade');
            $table->date('preferred_date');
            $table->string('preferred_time_slot')->nullable();
            $table->text('pickup_address');
            $table->string('contact_phone');
            $table->string('assigned_courier_name')->nullable();
            $table->string('assigned_courier_phone')->nullable();
            $table->foreignId('assigned_by_admin_id')->nullable()->constrained('users')->nullOnDelete();
            $table->enum('status', [
                'requested',
                'assigned',
                'collected',
                'delivered_to_lab',
                'failed',
                'cancelled',
                'completed' // Added for final completion state
            ])->default('requested');
            $table->text('admin_notes')->nullable();
            $table->text('failure_reason')->nullable();
            $table->timestamp('collected_at')->nullable();
            $table->timestamp('delivered_to_lab_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kit_pickups');
    }
};
