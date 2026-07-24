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
        Schema::create('pickup_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('kit_id')->nullable()->constrained('kits')->nullOnDelete();
            $table->string('kit_name');
            $table->string('kit_icon', 10)->default('🧬');
            $table->date('pickup_date')->nullable();
            $table->string('time_slot', 50)->nullable();
            $table->text('address');
            $table->string('contact_phone', 30)->nullable();
            $table->text('notes')->nullable();
            $table->text('admin_notes')->nullable();
            $table->enum('status', ['pending','scheduled','collected','cancelled', 'delivered_to_lab','failed'])->default('pending');
            $table->timestamp('collected_at')->nullable();
            $table->timestamp('cancelled_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pickup_requests');
    }
};
