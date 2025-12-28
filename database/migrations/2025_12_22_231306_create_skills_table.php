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
        Schema::create('skills', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique(); // URL-friendly identifier
            $table->string('category'); // 'technical', 'soft', 'language', etc.
            $table->integer('proficiency_level'); // 1-100 percentage
            $table->integer('years_experience')->default(0);
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            
            // English content
            $table->string('name_en');
            $table->text('description_en')->nullable();
            $table->json('keywords_en')->nullable(); // Related keywords
            $table->json('certifications_en')->nullable(); // Certifications in English
            
            // Arabic content
            $table->string('name_ar');
            $table->text('description_ar')->nullable();
            $table->json('keywords_ar')->nullable(); // Related keywords in Arabic
            $table->json('certifications_ar')->nullable(); // Certifications in Arabic
            
            // Metadata
            $table->string('icon')->nullable(); // Icon or image
            $table->string('color')->nullable(); // Skill color for visualization
            $table->json('projects_count')->nullable(); // Number of projects using this skill
            $table->timestamps();
            
            // Indexes
            $table->index(['category', 'is_active', 'sort_order']);
            $table->index(['proficiency_level', 'is_active']);
            $table->index('slug');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('skills');
    }
};
