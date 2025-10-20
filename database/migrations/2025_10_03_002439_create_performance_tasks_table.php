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
        Schema::create('performance_tasks', function (Blueprint $table) {
            $table->id();

            // Relationships
            $table->foreignId('subject_id')->constrained()->onDelete('cascade');
            $table->foreignId('section_id')->constrained()->onDelete('cascade');
            $table->foreignId('instructor_id')->constrained('users')->onDelete('cascade');

            // Task info
            $table->string('title');
            $table->text('description')->nullable();
            $table->integer('max_attempts')->default(2);
            $table->integer('xp_reward')->default(50);
            $table->timestamp('due_date')->nullable();
            $table->timestamp('late_until')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('performance_tasks');
    }
};
