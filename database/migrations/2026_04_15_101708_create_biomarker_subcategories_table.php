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
        Schema::create('biomarker_subcategories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('biomarker_category_id')->constrained('biomarker_categories')->onDelete('cascade');
            $table->string('title');
            $table->string('description')->nullable();
            $table->tinyInteger('status')->default(1)->comment('1=active,0=inactive');
            $table->integer('min_range')->nullable();
            $table->integer('max_range')->nullable();
            $table->string('unit')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('biomarker_subcategories');
    }
};
