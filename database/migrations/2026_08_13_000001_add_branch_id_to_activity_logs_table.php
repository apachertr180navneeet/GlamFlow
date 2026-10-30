<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('activity_logs', 'branch_id')) {
            Schema::table('activity_logs', function (Blueprint $table) {
                $table->foreignId('branch_id')->nullable()->after('user_id')->constrained()->nullOnDelete();
            });

            // Backfill branch_id from users table for existing activity logs
            try {
                DB::statement("
                    UPDATE activity_logs 
                    INNER JOIN users ON activity_logs.user_id = users.id 
                    SET activity_logs.branch_id = users.branch_id 
                    WHERE activity_logs.user_id IS NOT NULL AND users.branch_id IS NOT NULL
                ");
            } catch (\Exception $e) {
                // Ignore if SQL query fails on different DB engines
            }
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('activity_logs', 'branch_id')) {
            Schema::table('activity_logs', function (Blueprint $table) {
                $table->dropForeign(['branch_id']);
                $table->dropColumn('branch_id');
            });
        }
    }
};
