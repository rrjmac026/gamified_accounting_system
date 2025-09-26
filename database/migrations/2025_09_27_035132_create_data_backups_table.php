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
        Schema::create('data_backups', function (Blueprint $table) {
            $table->id();
            $table->string('backup_name');
            $table->string('file_path');
            $table->enum('backup_type', ['full', 'incremental', 'differential'])->default('full');
            $table->bigInteger('file_size')->nullable();
            $table->enum('status', ['pending', 'completed', 'failed'])->default('pending');
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->timestamp('backup_date')->nullable();
            $table->timestamp('retention_until')->nullable();
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('data_backups');
    }
};
