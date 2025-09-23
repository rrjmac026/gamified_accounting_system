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
        Schema::create('quizzes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('task_id')->constrained()->onDelete('cascade');

            // Template-related fields
            $table->json('csv_template_headers')->nullable(); 
            $table->string('template_name')->nullable();
            $table->text('template_description')->nullable();

            // Quiz type and content
            $table->enum('type', ['multiple_choice', 'identification', 'true_false']);
            $table->text('question_text');
            $table->json('options')->nullable();       // For multiple choice / true-false
            $table->string('correct_answer')->nullable();
            $table->integer('points')->default(1);

            // File upload
            $table->string('quiz_file_path')->nullable();

            // Optional rani for Excel checking
            $table->string('cell')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quizzes');
    }
};
