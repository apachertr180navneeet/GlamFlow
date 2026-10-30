<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('appointments', function (Blueprint $table) {
            $table->decimal('cash_amount', 10, 2)->default(0.00)->after('paid_amount');
            $table->decimal('upi_amount', 10, 2)->default(0.00)->after('cash_amount');
            $table->decimal('card_amount', 10, 2)->default(0.00)->after('upi_amount');
        });

        // Backfill cash_amount and upi_amount for existing paid appointments
        DB::table('appointments')->where('payment_type', 'cash')->where('paid_amount', '>', 0)->update([
            'cash_amount' => DB::raw('paid_amount')
        ]);

        DB::table('appointments')->where('payment_type', 'upi')->where('paid_amount', '>', 0)->update([
            'upi_amount' => DB::raw('paid_amount')
        ]);

        DB::table('appointments')->where('payment_type', 'card')->where('paid_amount', '>', 0)->update([
            'card_amount' => DB::raw('paid_amount')
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('appointments', function (Blueprint $table) {
            $table->dropColumn(['cash_amount', 'upi_amount', 'card_amount']);
        });
    }
};
