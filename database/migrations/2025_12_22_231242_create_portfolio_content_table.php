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
        Schema::create('portfolio_content', function (Blueprint $table) {
            $table->id();
            $table->string('key'); // Key for content identification
            $table->string('type'); // Type: 'hero', 'about', 'service', 'project', etc.
            $table->string('language', 2)->default('en'); // 'en' or 'ar'
            
            // English content
            $table->text('title_en')->nullable();
            $table->text('subtitle_en')->nullable();
            $table->longText('description_en')->nullable();
            $table->json('metadata_en')->nullable(); // For additional structured data
            
            // Arabic content
            $table->text('title_ar')->nullable();
            $table->text('subtitle_ar')->nullable();
            $table->longText('description_ar')->nullable();
            $table->json('metadata_ar')->nullable(); // For additional structured data
            
            // Common fields
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->string('category')->nullable(); // For grouping content
            $table->timestamps();
            
            // Indexes for better performance
            $table->index(['language', 'type']);
            $table->index(['type', 'is_active']);
            $table->index(['language', 'is_active']);
            
            // Unique constraint on key and language combination
            $table->unique(['key', 'language']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('portfolio_content');
    }
};
