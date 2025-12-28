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
        Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique(); // URL-friendly identifier
            $table->string('icon'); // Icon identifier (e.g., 'code', 'brain', 'microchip')
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            
            // English content
            $table->string('title_en');
            $table->text('description_en');
            $table->json('technologies_en')->nullable(); // Array of technologies
            
            // Arabic content
            $table->string('title_ar');
            $table->text('description_ar');
            $table->json('technologies_ar')->nullable(); // Array of technologies in Arabic
            
            // Metadata
            $table->json('features_en')->nullable(); // Array of service features
            $table->json('features_ar')->nullable(); // Array of service features in Arabic
            $table->string('image_url')->nullable(); // Service image
            $table->timestamps();
            
            // Indexes
            $table->index(['is_active', 'sort_order']);
            $table->index('slug');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('services');
    }
};
