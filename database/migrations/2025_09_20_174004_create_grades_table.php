<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('grades', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained()->onDelete('cascade');
            $table->foreignId('subject_id')->constrained()->onDelete('cascade');
            $table->string('semester');
            $table->string('academic_year');
            $table->decimal('final_grade', 3, 2)->nullable(); // e.g., 1.00 to 5.00
            $table->string('remarks')->nullable(); // Passed, Failed, INC
            $table->timestamps();

            $table->unique(['student_id', 'subject_id', 'semester', 'academic_year'], 'unique_student_subject');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('grades');
    }
};
