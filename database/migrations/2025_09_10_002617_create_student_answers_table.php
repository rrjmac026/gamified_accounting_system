<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStudentAnswersTable extends Migration
{
    public function up()
    {
        Schema::create('student_answers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('students')->onDelete('cascade');
            $table->foreignId('quiz_id')->constrained('quizzes')->onDelete('cascade');
            $table->text('answer');
            $table->boolean('is_correct')->default(false);
            $table->integer('score')->default(0);
            $table->timestamps();

            // Add unique constraint to prevent duplicate answers
            $table->unique(['student_id', 'quiz_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('student_answers');
    }
}
