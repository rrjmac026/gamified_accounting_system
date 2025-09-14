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

        $table->enum('type', ['multiple_choice', 'identification', 'true_false']);
        $table->text('question_text');
        $table->json('options')->nullable();     // used for multiple choice / true-false
        $table->string('correct_answer')->nullable(); // expected answer
        $table->integer('points')->default(1);
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
