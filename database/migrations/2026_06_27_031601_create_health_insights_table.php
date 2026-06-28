<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('health_insights', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();

            // Longevity Score
            $table->unsignedTinyInteger('longevity_score')->default(0);       // 0–100
            $table->unsignedTinyInteger('previous_score')->default(0);
            $table->integer('score_improvement')->default(0);                  // can be negative
            $table->date('score_since')->nullable();

            // Biological markers summary
            $table->decimal('biological_age_offset', 5, 1)->default(0);       // e.g. -2.4
            $table->unsignedTinyInteger('sleep_index')->default(0);            // %
            $table->string('cardio_fitness', 30)->default('Unknown');          // Excellent / Good / Fair

            // Markers optimal count
            $table->unsignedTinyInteger('markers_optimal')->default(0);
            $table->unsignedTinyInteger('markers_total')->default(0);

            // Test kit metadata
            $table->string('kit_number', 50)->nullable();
            $table->date('test_date')->nullable();
            $table->string('analysis_status', 30)->default('pending');         // pending | complete | processing

            // Alert / banner message
            $table->string('alert_message', 255)->nullable();

            // Retest reminder
            $table->date('retest_reminder_date')->nullable();
            $table->string('retest_note', 255)->nullable();

            $table->timestamps();
        });

        // ── Biomarker Results ──────────────────────────────────────────────
        Schema::create('health_biomarkers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('health_insight_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();

            $table->string('name', 100);                     // LDL Cholesterol, Vitamin D …
            $table->string('slug', 100);                     // ldl_cholesterol, vitamin_d …
            $table->decimal('value', 10, 2);
            $table->string('unit', 30)->nullable();          // mg/dL, ng/mL …
            $table->decimal('range_min', 10, 2)->nullable();
            $table->decimal('range_max', 10, 2)->nullable();
            $table->decimal('previous_value', 10, 2)->nullable();
            $table->decimal('change_percent', 6, 2)->default(0); // positive = up, negative = down
            $table->string('status', 30)->default('optimal'); // optimal | needs_attention | low | high
            $table->string('focus_category', 30)->default('stable'); // primary_focus | improving | stable
            $table->string('priority', 20)->default('normal');        // high | normal | low
            $table->text('note')->nullable();                // wellness-tone suggestion
            $table->string('icon', 10)->nullable();          // emoji icon
            $table->boolean('is_trending_up')->default(true);
            // Sparkline: store as JSON array of ~7 data points
            $table->json('trend_points')->nullable();        // e.g. [28,30,32,30,34,36,35]

            $table->timestamps();
        });

        // ── Smart Insights ────────────────────────────────────────────────
        Schema::create('health_smart_insights', function (Blueprint $table) {
            $table->id();
            $table->foreignId('health_insight_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();

            $table->string('title', 255);
            $table->text('body');
            $table->string('impact_label', 255)->nullable();
            $table->string('priority', 20)->default('normal');   // high | normal | low
            $table->string('badge_label', 50)->nullable();        // High Priority / Good / Stable
            $table->string('badge_type', 20)->default('normal'); // high | good | stable | normal
            $table->unsignedSmallInteger('sort_order')->default(0);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('health_smart_insights');
        Schema::dropIfExists('health_biomarkers');
        Schema::dropIfExists('health_insights');
    }
};