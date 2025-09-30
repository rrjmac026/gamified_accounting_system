<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('student_transaction_entries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('transaction_id')->constrained('transactions')->onDelete('cascade');
            $table->integer('attempts')->default(0);
            $table->foreignId('account_id')->constrained('accounts')->onDelete('cascade');
            $table->decimal('debit', 15, 2)->default(0);
            $table->decimal('credit', 15, 2)->default(0);
            $table->enum('status', ['correct', 'incorrect'])->nullable(); // optional grading
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('student_transaction_entries');
    }
};
