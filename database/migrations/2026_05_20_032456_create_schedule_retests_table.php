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
        Schema::create('schedule_retests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('kit_id')->constrained('kits')->onDelete('cascade');
            
            $table->string('original_inv_code')->nullable()->index(); 
            
            $table->date('retest_date');
            $table->time('retest_time')->nullable();
            
            $table->tinyInteger('status')->default(1)->comment('1=Scheduled, 2=Completed, 3=Cancelled');
            
            $table->text('admin_notes')->nullable();
            $table->text('user_notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('schedule_retests');
    }
};
