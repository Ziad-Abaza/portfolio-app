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
        Schema::create('statistics', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique(); // Unique key for statistic
            $table->string('type'); // 'number', 'percentage', 'counter'
            $table->bigInteger('value')->default(0);
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            
            // English content
            $table->string('label_en'); // e.g., "Projects Completed"
            $table->text('description_en')->nullable();
            
            // Arabic content
            $table->string('label_ar'); // e.g., "المشاريع المكتملة"
            $table->text('description_ar')->nullable();
            
            // Metadata
            $table->string('icon')->nullable(); // Icon identifier
            $table->string('color')->nullable(); // Color for display
            $table->string('prefix')->nullable(); // e.g., "$", "€"
            $table->string('suffix')->nullable(); // e.g., "+", "%"
            $table->json('metadata')->nullable(); // Additional data
            $table->timestamps();
            
            // Indexes
            $table->index(['is_active', 'sort_order']);
            $table->index('key');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('statistics');
    }
};
