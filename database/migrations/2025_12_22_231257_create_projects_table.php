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
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique(); // URL-friendly identifier
            $table->string('category'); // 'web', 'ai', 'iot', etc.
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->date('completed_at')->nullable();
            
            // English content
            $table->string('title_en');
            $table->text('description_en');
            $table->longText('content_en')->nullable(); // Detailed project description
            $table->json('technologies_en'); // Array of technologies
            $table->json('challenges_en')->nullable(); // Project challenges
            $table->json('solutions_en')->nullable(); // Implemented solutions
            
            // Arabic content
            $table->string('title_ar');
            $table->text('description_ar');
            $table->longText('content_ar')->nullable(); // Detailed project description in Arabic
            $table->json('technologies_ar'); // Array of technologies in Arabic
            $table->json('challenges_ar')->nullable(); // Project challenges in Arabic
            $table->json('solutions_ar')->nullable(); // Implemented solutions in Arabic
            
            // Links and media
            $table->string('github_url')->nullable();
            $table->string('live_url')->nullable();
            $table->string('demo_url')->nullable();
            $table->json('images')->nullable(); // Array of project images
            $table->string('thumbnail_url')->nullable();
            
            // Metadata
            $table->json('tags')->nullable(); // Project tags
            $table->json('metadata')->nullable(); // Additional project data
            $table->timestamps();
            
            // Indexes
            $table->index(['is_active', 'sort_order']);
            $table->index(['is_featured', 'is_active']);
            $table->index('category');
            $table->index('slug');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};
