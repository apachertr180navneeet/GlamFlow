<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('staffs', function (Blueprint $table) {
            $table->dropColumn(['commission_type', 'commission_value']);
        });
    }

    public function down(): void
    {
        Schema::table('staffs', function (Blueprint $table) {
            $table->string('commission_type')->nullable()->comment('percentage, fixed');
            $table->decimal('commission_value', 10, 2)->default(0);
        });
    }
};
