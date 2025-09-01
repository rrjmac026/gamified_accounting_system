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
            $table->text('feedback');
            $table->integer('rating')->nullable();
            $table->string('type')->default('task'); // task, general, etc.
            $table->enum('sentiment', ['positive', 'neutral', 'negative'])->nullable();
            $table->boolean('is_anonymous')->default(false);
            $table->timestamp('feedback_date');
            $table->timestamps();
            
            // Indexes
            $table->index(['student_id', 'task_id']);
            $table->index('feedback_date');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('feedback_records');
    }
};
