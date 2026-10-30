<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        try {
            DB::statement('ALTER TABLE whatsapp_logs CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci');
            DB::statement('ALTER TABLE sms_logs CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci');
        } catch (\Exception $e) {
            // Ignore if DB not reachable during build
        }
    }

    public function down(): void
    {
    }
};
