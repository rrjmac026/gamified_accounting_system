<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description');
            $table->enum('type', ['assignment', 'exercise', 'quiz', 'project']);
            $table->foreignId('subject_id')->constrained()->onDelete('cascade');
            $table->foreignId('instructor_id')->constrained()->onDelete('cascade');
            $table->integer('difficulty_level');
            $table->integer('max_score');
            $table->integer('xp_reward');
            $table->timestamp('due_date');
            $table->unsignedInteger('retry_limit')->default(1); // how many retries allowed 
            $table->unsignedInteger('late_penalty')->nullable(); // optional XP penalty for late submissions ikaw bahalag pila imong gusto
            $table->text('instructions');
            $table->enum('status', ['draft', 'pending', 'active', 'completed', 'archived'])->default('pending');
            $table->boolean('is_active')->default(true);
            $table->boolean('auto_grade')->default(false);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('tasks');
    }
};
