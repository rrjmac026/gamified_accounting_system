<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('course');
            $table->string('year_level'); // e.g. "1st Year", "2nd Year"
            $table->string('section')->nullable();
            $table->unsignedInteger('total_xp')->default(0);
            $table->unsignedInteger('current_level')->default(1);
            $table->decimal('performance_rating', 5, 2)->default(0.00);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};