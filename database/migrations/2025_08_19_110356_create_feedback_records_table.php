<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('feedback_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained()->onDelete('cascade');
            $table->foreignId('task_id')->nullable()->constrained()->onDelete('set null');
            
            // Main feedback fields
            $table->enum('feedback_type', ['general', 'improvement', 'question'])->default('general');
            $table->text('feedback_text');
            $table->json('recommendations')->nullable(); // Store as JSON array
            $table->integer('rating')->nullable(); // 1-5 stars
            
            // Metadata fields
            $table->timestamp('generated_at')->nullable();
            $table->boolean('is_read')->default(false);
            $table->boolean('is_anonymous')->default(false);
            
            // Legacy fields (if you want to keep them for compatibility)
            $table->text('feedback')->nullable(); // pwede ra e remove
            $table->string('type')->default('task'); // pwede ra e remove
            $table->enum('sentiment', ['positive', 'neutral', 'negative'])->nullable();
            $table->timestamp('feedback_date')->nullable();
            
            $table->timestamps();
            
            // Indexes
            $table->index(['student_id', 'task_id']);
            $table->index('generated_at');
            $table->index('feedback_type');
            $table->index('is_read');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('feedback_records');
    }
};