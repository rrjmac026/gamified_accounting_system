<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('performance_tasks', function (Blueprint $table) {
            $table->timestamp('due_date')->nullable()->after('xp_reward');
            $table->timestamp('late_until')->nullable()->after('due_date');
        });
    }

    public function down(): void
    {
        Schema::table('performance_tasks', function (Blueprint $table) {
            $table->dropColumn(['due_date', 'late_until']);
        });
    }
};

