<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('accounts', function (Blueprint $table) {
            $table->id();
            $table->string('name');                // Account name, e.g., Cash, Revenue
            $table->string('code')->nullable();   // Optional account code
            $table->enum('type', ['Asset','Liability','Equity','Revenue','Expense']);
            $table->text('description')->nullable();
            $table->enum('normal_balance', ['debit','credit'])->default('debit');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('accounts');
    }
};
