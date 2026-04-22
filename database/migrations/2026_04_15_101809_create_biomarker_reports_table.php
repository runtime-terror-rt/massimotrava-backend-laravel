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
        Schema::create('biomarker_reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('kit_id')->constrained('kits')->onDelete('cascade');
            $table->foreignId('biomarker_category_id')->constrained('biomarker_categories')->onDelete('cascade');
            $table->foreignId('biomarker_subcategory_id')->constrained('biomarker_subcategories')->onDelete('cascade');
            $table->decimal('value', 10, 2);
            $table->string('unit')->nullable();
            $table->string('inv_code')->nullable();
            $table->tinyInteger('status')->default(0)->comment('1=active,0=inactive');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('biomarker_reports');
    }
};
